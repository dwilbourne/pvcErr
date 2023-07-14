
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
parameter to verifyLibrary is the instantiated XData object for your library.

Here's an example of the single test written to test all the exceptions in the pvcRegex library::

    <?php

    /**
    * @author: Doug Wilbourne (dougwilbourne@gmail.com)
    */

    declare (strict_types=1);

    namespace pvcTests\regex\err;

    use pvc\err\XDataTestMaster;
    use pvc\regex\err\_RegexXData;

    /**
    * Class _RegexXDataTest
    */
    class _RegexXDataTest  extends XDataTestMaster
    {
        /**
         * @function testPvcRegexExceptionLibrary
         * @covers \pvc\regex\err\_RegexXData::getXMessageTemplates
         * @covers \pvc\regex\err\_RegexXData::getLocalXCodes
         * @covers \pvc\regex\err\RegexBadPatternException::__construct
         * @covers \pvc\regex\err\RegexInvalidDelimiterException::__construct
         * @covers \pvc\regex\err\RegexInvalidMatchIndexException::__construct
         */
        public function testPvcRegexExceptionLibrary(): void
        {
            $xData = new _RegexXData();
            self::assertTrue($this->verifyLibrary($xData));
        }
    }

The tedious part is listing the @covers annotation for each exception in the library, and the rest of it is pretty
fast to generate (by a lot when compared to writing a separate test for each exception class).
