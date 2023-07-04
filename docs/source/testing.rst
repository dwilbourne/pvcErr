
=======
Testing
=======

I am hopelessly OCD.  And I know that Sebastian Bergmann said "don't write useless tests.".  But I'm not really
comfortable until I see 100% coverage.

If you would like to test each of your xData libraries, the pvcErr library comes with a class you can use.  The class
is XDataTestMaster.  In the appropriate tests directory in your project, construct a class which extends
XDataTestMaster.  It's important to know that XDataTestMaster extends TestCase from PHPUnit.  It does not matter
which version of PHPUnit you are using, but you will need to include PHPUnit in the require-dev section of your
composer.json file (like it wasn't there already, right?).

There is a method in XDataTestMaster called verifyLibrary.  It makes sure the exceptions in your XData file match the
exceptions that actually exist in the library, makes sure your exception codes are unique integers, etc.  The
parameter to verifylibrary is the instantiated XData object for your library.

Here's an example of the method definition::

    public function testPvcRegexExceptionLibrary(): void
    {
        $xData = new _RegexXData();
        self::assertTrue($this->verifylibrary($xData));
    }

