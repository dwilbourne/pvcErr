
.. toctree::
   :maxdepth: 2
   :caption: Contents:


pvcErr
============

The pvcErr package provides an organized (though admittedly unorthodox) approach to constructing and throwing
exceptions in php.  The pvc libraries use this approach throughout.  If you decide you like this approach, the
library is constructed with a lightweight hook that allows you to use the same conventions and the pvcException code in
your own project.


Design Points
#############

* It provides a comprehensive way of dealing with exception codes that guarantees uniqueness of codes between packages.
  Part of value here is that it is easier (less typing) to search logfiles for codes than it is messages.

* It provides a set of conventions that makes maintaining exception codes and messages easy.
  
* It implements named parameter substitution into exception message templates. For example, a message template could look like this "Invalid filename: ${filename}", as opposed to the order-dependent sprintf format "Invalid filename:%s".


Discussion
##########

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

pvcException supposes that a "library" of exceptions is a *directory* that contains exception classes plus an exception
data class (XData) that is used to construct the exceptions in that library.  This exception data class *must extend*
pvc\err\XDataAbstract and must implement the following methods::


  abstract protected function getLocalXCodes() : array;
  abstract protected function getXMessageTemplates(): array;


(The naming convention of 'XCode' or 'XMessage' is simply shorter than typing 'ExceptionCode' or 'ExceptionMessage').

The keys in the arrays returned by getXCodes and getXMessageTemplates should be the fully qualified class strings of
the exceptions in the library.  This convention  allows you to take advantage of autocompletion in your IDE.  It also
allows the code to verify that all the exceptions in the library have codes and messages, that the codes are unique,
that there are no "extra exceptions" that have no data, etc.

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
top of the directory which is holding my exceptions.  As an example, I have an exception library for the exceptions
that can be thrown in a pvc library for handling tree data structures.  The exception data file is called
"_TreeExceptionData".

Creating the classes for your exceptions is now quite simple.  In the same directory in which your exception data
class lives, you create "empty exceptions" that extend base exceptions and have a very small footprint. You DO want to
provide the construction signature and pass the parameters to the parent class(es).  Again, taking advantage of
autocompletion in your IDE, this simplifies things when you go to throw an exception in your code.  The IDE will
prompt you for the parameters, so you don't have to refer to your exception data file to figure out the name of the
parameters.

Speaking of parameters, as you can see from the example above, the code uses a template format of "${paramname}",
where paramname is the name of a parameter in the constructor of the exception.  Like all of PHP, the names are
case-sensitive.  Make sure that the dummy variable(s) in the exception constructor match the identifier within the
braces of your template variables in your exception data file.

The parameters to your exceptions should be scalar and convertible to strings (so typed as strings or int is a good
rule of thumb).  It will convert booleans to either 'true' or 'false'.  If you create a parameter with something more
complex, like an object, the library will simply substitute the\ *type*\ of the thing you passed as a parameter into
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

So when you go to throw this exception, what happens?

As you can see, this exception extends LogicException, which is a "pvc branded" exception (hence the "use" statemment
in the code block above).  LogicException extends Exception, which is the top level exception of pvc exceptions.
pvc\err\Exception holds the code that is used to construct the exception code and message.  Any exceptions that you
write must extend pvc\err\Exception in some way.  In general, I want to be able to distinguish between Runtime exceptions and
Logic exceptions, so all of the exceptions in the pvc libraries extend one of those two and they are included in this package.
Of course you can create additional categorizations if you choose (PDO exception, stream exception, etc).

The final task we need to address is how to insure uniqueness of codes between libraries.  The basic thought process
is that we create a map between namespaces (e.g. libraries) and integer values. Of course, the namespaces
correspond to the namespaces of your exception libraries.  The prefixes in the array are unique integers.  These
prefixes will be prepended to the local exception codes defined in your exception library data classes.  This is the
mechanism that guarantees uniqueness among exception codes.

In terms of implementation, there are actually two mechanisms that work in parallel.  Internally, i.e.
only as pertains to exceptions defined in the pvc libraries, I use
a static class to store the namespaces and integers.  If you install this package and look in the vendor directory
under pvc\err\src, you will see XCodePrefixes.php.

But in order to make the package usable for others, the code looks for an environment variable named "XCodePrefixes".
If such a variable exists, then the value of the variable should be the filepath of *your* exception code prefixes.
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


#############################
Installation and Dependencies
#############################

You can install the pvcException package through composer.::


    require "pvc/err"


Note that the pvcException package requires pvcInterfaces, so you will see that show up in your vendor/pvc directory. It
also requires Nikita Popov's PHP Parser because there's a spot in the code where is necessary to parse php files in
order to extract the class string from a file.

#####
Setup
#####

1. Decide where you want to keep your exception code prefixes file.  If you are using a framework that has a config
directory, that would be a sensible choice, or simply perhaps in the root of your src directory.

2. as part of bootstrapping your application, use putenv() to set "XCodePrefixes" to the filepath chosen in step 1.

Update this file as often as you create a new exception library.  Recall that creating an exception library
consists of

* creating the directory in which the exceptions live.
* creating a class inside that directory which extends pvc's XDataAbstract class and stores messages and "local" codes.
* create exceptions where the constructor consists of the parameter(s) to the message followed by any previous exception you are adding to the exception stack.

#####
Usage
#####

This is how to throw the exception::

    throw new MyException($param1, $param2, $previous);

