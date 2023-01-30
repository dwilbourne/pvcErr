The pvcErr package provides an organized approach to constructing and creating exceptions in php.  The pvc libraries 
use this approach throughout.  In particular, it provides a comprehensive way of dealing with exception codes that 
guarantees uniqueness of codes between packages.  Part of value here is that it is easier (less typing) to search 
logfiles for codes than it is messages.

The signature for creating an exception in php provides the exception constructor with a text string ("message"),
an integer code ("code"), and the previous exception/error that was thrown ("prev").  One's first natural 
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

Another complexity of managing codes comes up in the context of managing multiple libraries of exceptions.  I tend 
to put all of my exceptions into a single directory for a given set of classes.  But if you are constructing multiple 
packages that need to use a common error code numbering system, you need a mechanism to insure global uniqueness 
between the packages.  

pvcErr supposes that a "library" of exceptions is a *directory* that contains exception classes plus an exception 
data class that is used to construct the exceptions in that library.  The exception data class extends 
pvc\err\XDataAbstract and must implement the following methods:

	abstract protected function getCodes() : array;
	abstract protected function getMessages(): array;
    abstract protected function getNamespace(): string;
    abstract protected function getDirectory(): string;


The keys in the arrays returned by getCodes and getMessages should be the fully qualified class strings of the 
exceptions in the library.  This convention  allows you to take advantage of autocompletion in your IDE.  It also 
allows the code to verify that all the exceptions in the library have codes and messages, that the codes are unique, 
that there are no "extra exceptions" that have no data, etc.

Here is an example of a small exception data class:

class _ExceptionData extends XDataAbstract
{
    public function getCodes() : array
    {
        return [
            InvalidArrayIndexException::class => 1001,
            InvalidArrayValueException::class => 1002,
            InvalidAttributeNameException::class => 1003,
            InvalidFilenameException::class => 1004,
            InvalidPHPVersionException::class => 1005,
            PregMatchFailureException::class => 1006,
            PregReplaceFailureException::class => 1007,
        ];
    }

    public function getMessages() : array
    {
        return [
    		InvalidArrayIndexException::class => 'Invalid array index %s.',
	    	InvalidArrayValueException::class => 'Invalid array value %s.',
		    InvalidAttributeNameException::class => 'Attribute %s does not exist or is not directly accessible.',
    		InvalidFilenameException::class => 'filename %s is not valid.',
	    	InvalidPHPVersionException::class => 'Invalid PHP version - must be at least %s',
		    PregMatchFailureException::class => 'preg_match failed: regex=%s; subject=%s;',
    		PregReplaceFailureException::class => 'preg_replace failed: regex=%s; subject=%s; replacement=%s',
	    ];
    }

    protected function getNamespace() : string
    {
		return  __NAMESPACE__;	
    }

    protected function getDirectory() : string
    {
		return  __DIR__;	
    }
}

By keeping all the codes and all the message texts in one file, it is far easier to keep local codes and message 
conventions consistent in the library.  You can name the exception data class file anything you want.  I typically 
use a filename that starts with an underscore ("_") so that the file system automatically sorts it to appear at the 
top of the directory which is holding my exceptions.  As an example, I have a library data class called 
"_TreeExceptionData", which is a library for all the exceptions that can be thrown in the course of creating and 
manipulating an abstract tree structure.

Creating the classes for your exceptions is now quite simple.  In the same directory in which your exception data 
class lives, you create "empty exceptions" that extend base exceptions but have no body. For example, here's an 
example of an exception that goes with the example above:

class InvalidArrayIndexException extends LogicException {}


The final task we need to address is how to insure uniqueness of codes between libraries.  This is accomplished 
through a single configuration file that handles persistence between requests.  This file can can reside wherever 
you would like as long as it is readable and *writeable*.  The file is formatted in json and is a simple array that 
maps namespaces to "library codes".  As you might guess, the namespaces correspond to the namespaces of your 
exception libraries.  The codes in the array are unique integers.  These integers are "prefixes" which will be 
prepended to the local exception codes defined in your exception library data classes.  This is the mechanism that 
guarantees uniqueness among exception codes.

It is *not necessary* for you to manually maintain this file.  In fact, you don't need to create it either.  You 
simply need to provide the filename and path to the constructor argument of the class ExceptionLibraryPrefixes.  
When you go to throw an exception, the exception factory uses this class to look up the library code for the 
namespace of the exception being thrown.  It parses the file to see if the exception namespace has already been 
registered.  If it has, it returns the library code.  If it has not, it registers the new namespace and allocates 
the namespace a new code and then returns that.  

The default "starting library code" is 1000 and increments the codes by 1 each time a new namespace is registered.  
If you prefer a different scheme for your codes, see the documentation for the XCodePrefixes class 
for the proper way to implement that.

So in case you are interested, here is an example of what the json configuration file might look like:

{
    "pvc\\Err\\pvc" : 1001,
    "pvc\\Err\\stock" : 1002,
    "pvc\\struct\\list" : 1003,
    "pvc\\struct\\range" : 1004,
    "pvc\\struct\\tree" : 1005,
    "pvc\\exif" : 1006,
    "pvc\\storage\\file" : 1007
}

The ExceptionLibraryPrefixes class maintains this file on its own: it will create the file, add codes to it as 
necessary and write those codes back to disk.

The last class to know about is the ExceptionFactory class.  This is the class you use to actually throw exceptions. 
It needs to be instantiated in your client class.  The cleanest way to get this class into your client class is via 
dependency injection.

Installation, Setup & Usage

You can install the pvcErr package through composer.

    require "pvc/err"




The cleanest way to use the exception factories is to create them in a dependency injection container and then 
inject them into the classes that need to throw exceptions.  In other words, in each class that throws an exception, 
create a protected attribute called $exceptionFactory.  That factory should be created in your DI 
container with a string of the correct namespace and the filepath of your library codes json file as the parameters to 
the constructor. As part of the DI creating your class, inject the factory.

You use the factory to create an exception using a method call: createPrefixingException(string $classString, array 
$messageParams = [], Throwable $previous = null).  Because of the setup done through the DI container, your code for 
throwing an exception looks like:

    throw $this->exceptionFactory->createPrefixingException($classString, $params, $previous).






