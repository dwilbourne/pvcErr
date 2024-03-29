==========
Discussion
==========

The Problem
###########

The standard signature for creating an exception in php provides the exception constructor with a text string
("message"), an integer code ("code"), and the previous exception/error that was thrown ("prev").  One's first natural
inclination is to define new exceptions by extending one of the stock php exceptions (for example, LogicException),
and define the class with its own message and code.  Something like the following::

    class myException extends LogicException {

        public function __construct(int $x, Throwable $prev)
        {
            $message = sprintf("The integer you supplied (%s) must be greater than 0.", $x);
            $code = 12;

            parent::__construct($message, $code, $prev);
        }
    }

This approach is easy to understand but difficult to manage when you start defining a largish number of exceptions.
For one, it's hard to keep your code numbering system straight.  What was the code you used in the last exception
that you wrote a couple of days ago?  Another challenge is to keep the same style of messages consistent from
exception to exception.  What is the language style like?  Do you want short messages, long messages?  What
conventions do you use for substituting data values into the message string?

Another complexity of managing codes comes up in the context of managing multiple libraries of exceptions.  I tend
to put all of my exceptions into a single directory for a given set of classes.  But if you are constructing a
larger application with multiple directories of exceptions or creating multiple
packages that need to use a common error code numbering system, you need a mechanism to insure global uniqueness
between different exception libraries.

The Answer
##########

pvcErr supposes that a "library" of exceptions is a *directory* that contains exception classes plus an exception
data class (XData) that is used to construct the exceptions in that library.  This exception data class *must extend*
pvc\\err\\XDataAbstract and must implement the following methods::


  abstract protected function getLocalXCodes() : array;
  abstract protected function getXMessageTemplates(): array;


(The naming convention of 'XCode' or 'XMessage' is simply shorter than typing 'ExceptionCode' or 'ExceptionMessage').

The keys in the arrays returned by getXCodes and getXMessageTemplates should be the fully qualified class strings of
the exceptions in the library.  This convention  allows you to take advantage of autocompletion in your IDE.  It also
allows the testing code to verify that all the exceptions in the library have codes and messages, that the codes are
unique, that there are no "extra exceptions" that have no data, etc.

Here is an example of a small exception data class::


  class _ExceptionData extends XDataAbstract
  {
    public function getLocalXCodes() : array
    {
        return [
            InvalidArrayIndexException::class => 1001,
            InvalidArrayValueException::class => 1002,
            InvalidFilenameException::class => 1003,
            InvalidPHPVersionException::class => 1004,
            PregMatchFailureException::class => 1005,
            PregReplaceFailureException::class => 1006,
        ];
    }

	  public function getXMessageTemplates() : array
    {
        return [
            InvalidArrayIndexException::class => 'Invalid array index ${index}.',
            InvalidArrayValueException::class => 'Invalid array value ${value}.',
            InvalidFilenameException::class => 'filename ${$filename} is not valid.',
            InvalidPHPVersionException::class => 'Invalid PHP version ${currentVersion} - must be at least ${minVersion}',
            PregMatchFailureException::class => 'preg_match failed: regex=${regex}; subject=${subject};',
            PregReplaceFailureException::class => 'preg_replace failed: regex=${regex}; subject=${subject}; replacement=${replacement}',
        ];
    }
  }


Keeping all the codes and all the message templates in one file makes it far easier to keep local codes and message
conventions consistent in the library.  You can name the exception data class file anything you want.  I typically
use a filename that starts with an underscore ("_") so that the file system automatically sorts it to appear at the
top of the directory which is holding my exceptions, even if phpcs complains about it not being in camel case.  As an
example, I have an exception library for the exceptions that can be thrown in a pvc library for handling tree data
structures.  The exception data file is called "_TreeExceptionData".

Creating the classes for your exceptions is now quite simple.  It is no longer necessary to bury a code and message
inside each exception.  Each exception has one line of code which calls the parent constructor and passes the message
parameters and $prev parameter up the inheritance chain.

Message Parameters
##################

Speaking of parameters, as you can see from the example above, the code uses a template format of "${paramname}",
where paramname is the name of a parameter in the constructor of the exception.  Like all of PHP, the names are
case-sensitive.  Make sure that the dummy variable(s) in the exception constructor match the identifier within the
braces of your template variables in your exception data file (the automated tests check for this anyway).

The parameters to your exceptions should be scalar and convertible to strings (so typed as strings or int is a good
rule of thumb).  It will convert booleans to either 'true' or 'false'.  If you create a parameter with something more
complex, like an object, the library will simply substitute the *type* of the thing you passed as a parameter into
the message.

For example, here's an exception that goes with the example above::

  use pvc\err\LogicException;

  class InvalidArrayIndexException extends LogicException
  {
    public function __construct(string $index, Throwable $previous = null)
    {
        parent::__construct($index, $previous);
    }
  }

There are a couple of rules about declaring parameters in the exceptions.  If there is an explicit constructor in
your exception, the rules are

* there must be at least one parameter
* except for the $prev parameter, the name of each parameter must match a variable name in the message
* the last parameter must be typed \Throwable and must have a default of null.

It is not necessary, however, to have an explicit constructor in your exception if the corresponding message has no
message variables in it.  In other words, imagine an exception message like "You should not have done that." (ok, not
a great message, but whatever).  The exception definition can be this simple::

    use pvc\err\LogicException;

    class MyNonsenseException extends LogicException
    {
    }

These rules are embedded in the automatic testing of your exception library (see the Testing section for more info).
If you break one of these parameter rules, the automatically generated tests will fail.

So when you go to throw this exception, what happens?

As you can see, this exception extends LogicException, which is a "pvc branded" exception (hence the "use" statemment
in the code block above).  LogicException extends Exception, which is the top level exception of pvc exceptions.
pvc\\err\\Exception holds the code that is used to construct the exception code and message.  Any exceptions that you
write must extend pvc\\err\\Exception in some way.  In general, I want to be able to distinguish between Runtime
exceptions and Logic exceptions, so all of the exceptions in the pvc libraries extend one of those two and they
are included in this package. Of course you can create additional categorizations if you choose (PDO exception,
stream exception, etc).

Exception Code Prefixes
#######################

So far, we've made it easy to ensure that the codes within an exception library are unique.
The final task we need to address is how to insure uniqueness of codes between libraries.  The basic thought process
is that we create a map between namespaces (e.g. libraries) and integer values. Of course, the namespaces
correspond to the namespaces of your exception libraries.  The prefixes in the array are unique integers.  These
prefixes will be prepended to the local exception codes defined in your exception library data classes.  This is the
mechanism that guarantees uniqueness among exception codes.

But in order to make the package usable for others, the XCodePrefixes class exposes a static method called
addXCodePrefix.  Ot takes two parameters, the first being the namespace of an exception code library and the second
being a unique positive integer greater than 1000.  When you bootstrap your application, just add your exception
library namespaces and their corresponding prefixes and the configuration is complete.  If you test your exception
library, it will fail if the library is not registered with XCodePrefixes as just described.  If you thrown an
exception using a library which is not registered, your integer exception code is returned with no prefix.

In order that I could create this library with no other dependencies, this file must be in php format.  Yaml and XML
formats would require a dependency on a parser.  Json would be a possibility since php can natively parse json, but
in the interests of keeping things as simple as possible......

Your exception data file should return an array whose form is described above:  a series of elements that maps
namespaces to integers.  For example::


  <?php

  return [
    'my\\namespace' => 1000,
    'another\\namespace' => 1001,
  ]


pvc reserves the "exception code address space" below 1000.  Your exception libraries can use any prefix integers you
want so long as they are greater than 999.  Using integers less than 1000 will result in an error (well... technically
an exception :)
