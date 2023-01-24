The pvcErr package provides an organized approach to constructing and creating exceptions in php.  The pvc libraries 
use this approach throughout.  In particular, it provides a comprehensive way of dealing with exception codes that 
guarantees uniqueness of codes between packages.  Part of value here is that it is easier (less typing) to search 
logfiles for codes than it is messages.

The signature for creating an exception in php provides the exception constructor with a text string ("message")
, an integer code ("code") and the previous exception/error that was thrown ("prev").  One's first natural 
inclination is to define new exceptions by extending one of the stock php exceptions (for example, LogicException), 
and define the class with its own message and code.  Something like the following:

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

Another complexity of managing codes comes up in the context of managing multiple libraries of exceptions.  We tend 
to put all of our exceptions into a single directory for a given set of classes.  But if you are constructing multiple 
packages that need to use a common error code numbering system, you need a mechanism to insure global uniqueness 
between the packages.  

pvcErr supposes that a "library" of exceptions is a *directory* that contains exception classes plus an exception 
factory class that is used to construct the exceptions in that library.  Think of this exception factory as a "local 
factory". It extends pvc\err\ExceptionFactoryAbstract and must implement the following methods:

	abstract protected function getCodes() : array;
	abstract protected function getMessages(): array;
    abstract protected function getNamespace(): string;


The keys in the arrays returned by getCodes and getMessages should be the fully qualified class strings of the 
exceptions in the library.  This convention  allows you to take advantage of autocompletion in your IDE.  It also 
allows the code to verify that all the exceptions in the library have codes and messages, that the codes are unique, 
that there are no "extras", etc.

Here is an example of a small local exception factory:

class _ExceptionFactory extends ExceptionFactoryAbstract
{
    protected array $codes = [
        InvalidArrayIndexException::class => 1001,
        InvalidArrayValueException::class => 1002,
        InvalidAttributeNameException::class => 1003,
        InvalidFilenameException::class => 1004,
        InvalidPHPVersionException::class => 1005,
        PregMatchFailureException::class => 1006,
        PregReplaceFailureException::class => 1007,
    ];

	protected array $messages = [
		InvalidArrayIndexException::class => 'Invalid array index %s.',
		InvalidArrayValueException::class => 'Invalid array value %s.',
		InvalidAttributeNameException::class => 'Attribute %s does not exist or is not directly accessible.',
		InvalidFilenameException::class => 'filename %s is not valid.',
		InvalidPHPVersionException::class => 'Invalid PHP version - must be at least %s',
		PregMatchFailureException::class => 'preg_match failed: regex=%s; subject=%s;',
		PregReplaceFailureException::class => 'preg_replace failed: regex=%s; subject=%s; replacement=%s',
	];

    protected function getNamespace() : string
    {
		return  __NAMESPACE__;	
    }

	protected function getCodes(): array
	{
		return $this->codes;
	}

	protected function getMessages(): array
	{
		return $this->messages;
	}
}

By keeping all the codes and all the message texts in one file, it is far easier to keep local codes and message 
conventions consistent in the library.

The final task we need to address is how to insure uniqueness of codes between libraries.  This is handled by 
ExceptionFactoryAbstract, which has a method called registerLibraryCodes(string $libraryCodeFile).  The pvc 
libraries contain a single library code file which provides library codes for each section of the pvc libraries.  When you 
install pvcErr via composer, this library code file is automatically installed in the same directory as 
ExceptionFactoryAbstract.  A library code file is a simple json file which, when read, provides a mapping between 
namespaces and library codes.  For example, here is an extract of the library code file that handles the pvc libraries.

{
    "LibraryCodes" : {
        "pvc\\Err\\pvc" : 1001,
        "pvc\\Err\\stock" : 1002,
        "pvc\\struct\\list" : 1003,
        "pvc\\struct\\range" : 1004,
        "pvc\\struct\\tree" : 1005,
        "pvc\\exif" : 1006,
        "pvc\\storage\\file" : 1007
    }
}

In order to extend this functionality and set of conventions into your own code, create your own library code file(s).  
The root of your source folder for your project might be a good place.  Create as many exception libraries as you 
want in the course of constructing you project.  Each time you create a new exception library, add an entry to the 
library codes file for the namespace of the exception library you are creating.  In order to use your exception 
factories, you will want to register your library codes with the factory when the factory is created.  Once 
registered, you can then create exceptions.  See the section on Usage for the full explanation on how to create the 
factory and create exceptions.

As you can imagine, ExceptionFactoryAbstract manages the logic involved in creating the actual exception.  The codes 
are creating by concatenating the local code to the library code in a string operation and then returning the 
concatenation as a casted integer.  For example, to build upon the small examples above, the code for 
"InvalidArrayIndexException" is "1001" (the library code for the namespace pvc\err\pvc) concatenated to "1001" (the 
"local code" from the library located in that namespace).  The result is 10011001.

Usage

The cleanest way to use the exception factories is to create them in a dependency injection container and then 
inject them into the classes that need to throw exceptions.  In other words, in each class that throws an exception, 
create a protected attribute called $exceptionFactory.  That factory should be created in your DI 
container with a string of the correct namespace and the filepath of your library codes json file as the parameters to 
the constructor. As part of the DI creating your class, inject the factory.

You use the factory to create an exception using a method call: createException(string $classString, array 
$messageParams = [], Throwable $previous = null).  Because of the setup done through the DI container, your code for 
throwing an exception looks like:

    throw $this->exceptionFactory->createException($classString, $params, $previous).






