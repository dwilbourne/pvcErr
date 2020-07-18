<?php declare(strict_types = 1);

namespace pvc\err\throwable;

/**
 * constants for error and exception codes.
 */
class ErrorExceptionConstants
{

    /**
     * codes for exceptions that are simply rebranding the stock exceptions that come with php
     */

    public const BAD_FUNCTION_CALL_EXCEPTION = 106;
    public const BAD_METHOD_CALL_EXCEPTION = 107;
    public const CLOSED_GENERATOR_EXCEPTION = 101;
    public const DOMAIN_EXCEPTION = 108;
    public const DOM_ARGUMENT_EXCEPTION = 1023;
    public const DOM_EXCEPTION = 1023;
    public const DOM_FUNCTION_EXCEPTION = 1023;
    public const ERROR_EXCEPTION = 103;
    public const EXCEPTION = 100;
    public const INTL_EXCEPTION = 104;
    public const INVALID_ARGUMENT_EXCEPTION = 109;
    public const INVALID_DATA_TYPE_EXCEPTION = 109;
    public const LENGTH_EXCEPTION = 110;
    public const LOGIC_EXCEPTION = 105;
    public const OUT_OF_BOUNDS_EXCEPTION = 115;
    public const OUT_OF_RANGE_EXCEPTION = 111;
    public const OVERFLOW_EXCEPTION = 116;
    public const RANGE_EXCEPTION = 118;
    public const REFLECTION_EXCEPTION = 113;
    public const RUNTIME_EXCEPTION = 114;

    public const PHAR_EXCEPTION = 112;
    public const PDO_EXCEPTION = 117;
    public const UNDERFLOW_EXCEPTION = 119;
    public const UNEXPECTED_VALUE_EXCEPTION = 120;
    public const SODIUM_EXCEPTION = 121;


    /**
     * codes for errors that are simply rebranding the stock exceptions that come with php
     */

    public const ERROR = 200;
    public const ARITHMETIC_ERROR = 201;
    public const DIVISION_BY_ZERO_ERROR = 202;
    public const ASSERTION_ERROR = 203;
    public const PARSE_ERROR = 204;
    public const TYPE_ERROR = 205;
    public const ARGUMENT_COUNT_ERROR = 206;


    /**
     * pvc exception codes
     */

    public const OUT_OF_CONTEXT_METHOD_CALL_EXCEPTION = 1000;
    public const INVALID_ARRAY_INDEX_EXCEPTION = 1000;
    public const INVALID_ARRAY_VALUE_EXCEPTION = 1000;
    public const INVALID_ATTRIBUTE_EXCEPTION = 1000;
    public const INVALID_ATTRIBUTE_NAME_EXCEPTION = 1000;
    public const INVALID_VALUE_EXCEPTION = 1000;
    public const INVALID_TYPE_EXCEPTION = 1000;
    public const INVALID_FILENAME_EXCEPTION = 1000;
    public const INVALID_PHP_VERSION_EXCEPTION = 1000;
    public const PREG_MATCH_FAILURE_EXCEPTION = 1000;
    public const PREG_REPLACE_FAILURE_EXCEPTION = 1000;
    public const UNSET_ATTRIBUTE_EXCEPTION = 1000;
    public const INVALID_LOCALE = 1023;
    public const FILESYSTEM_EXCEPTION = 1023;


    /**
     * Regex Error codes
     */

    public const REGEX_BAD_PATTERN_EXCEPTION = 1023;
    public const REGEX_UNSET_PATTERN_EXCEPTION = 1023;
    public const REGEX_BAD_MATCHES_INDEX_EXCEPTION = 1023;
    public const REGEX_SANITIZE_CHAR_EXCEPTION = 1023;

    /**
     * data structure exception codes
     */

    /**
     * Lists
     */

    public const LIST_VALIDATE_OFFSET_EXCEPTION = 1023;
    public const LIST_DUPLICATE_KEY_INDEX_EXCEPTION = 1023;
    public const LIST_KEY_INDEX_NOT_EXIST_EXCEPTION = 1023;


    /**
     * Tree nodes
     */
    public const TREENODE_INVALID_NODEID_EXCEPTION = 1023;
    public const TREENODE_INVALID_NODE_VALUE_EXCEPTION = 1023;
    public const TREENODE_INVALID_NODE_INDEX_EXCEPTION = 1023;
    public const TREENODE_ALREADY_SET_NODEID_EXCEPTION = 1023;
    public const TREENODE_ALREADY_SET_PARENT_EXCEPTION = 1023;
    public const TREENODE_ADD_CHILD_EXCEPTION = 1023;
    public const TREENODE_DELETE_CHILD_EXCEPTION = 1023;

    /**
     * Trees
     */
    public const TREE_ALREADY_SET_NODEID_EXCEPTION = 1023;
    public const TREE_ALREADY_SET_ROOT_EXCEPTION = 1023;
    public const TREE_INVALID_PARENT_NODE_EXCEPTION = 1023;
    public const TREE_INVALID_NODE_DATA_EXCEPTION = 1023;
    public const TREE_INVALID_NODE_TYPE_EXCEPTION = 1023;
    public const TREE_NODE_NOT_IN_TREE_EXCEPTION = 1023;
    public const TREE_DELETE_INTERIOR_NODE_EXCEPTION = 1023;
    public const CIRCULAR_GRAPH_EXCEPTION = 1023;

    /**
     * filesys
     */

    /**
     * findFile
     */

    public const INVALID_SEARCH_DIR_EXCEPTION = 1023;

    /**
     * fileAccessTrait
     */
    public const FILE_ACCESS_EXCEPTION = 1023;

    /**
     * mediatype
     */
    public const MEDIA_TYPE_DATA_CREATOR_FILE_ACCESS_EXCEPTION = 1023;
    public const CREATE_MEDIA_TYPE_DATA_CLASS_EXCEPTION = 1023;

    /**
     * Throwables Documenter
     */
    public const THROWABLES_DOCUMENTER_PARSE_EXCEPTION = 1023;

    /**
     * mediatype
     */
    public const MEDIA_TYPE_DATA_FILES_EXCEPTION = 1023;

    /**
     * Parsers
     */

    /**
     * NumberParser
     */

    public const INVALID_CONFIGURATION_SET_EXCEPTION = 1023;
    public const OPTION_CONFIGURATION_EXCEPTION = 1023;
    public const OPTION_SET_FORMAT_EXCEPTION = 1023;
    public const CALC_NUM_DECIMAL_PLACES_EXCEPTION = 1023;
    public const UNSET_SYMBOL_VALUE_EXCEPTION = 1023;
    public const SET_SYMBOL_TYPE_EXCEPTION = 1023;
    public const SET_SYMBOL_FROM_CHAR_EXCEPTION = 1023;
    public const INVALID_FORMAT_EXCEPTION = 1023;
    public const INVALID_SYMBOL_EXCEPTION = 1023;
    public const DUPLICATE_SYMBOL_VALUE_EXCEPTION = 1023;

    /**
     * file
     */

    /**
     * csv
     */

    public const INVALID_COLUMN_HEADING_EXCEPTION = 1023;

    /**
     * array_utils
     */
    public const CARTESIAN_PRODUCT_EXCEPTION = 1023;

    /**
     * NumberRange
     */

    public const SET_RANGE_EXCEPTION = 1023;

    /**
     * Xml Validator
     */
    public const LIB_XML_EXCEPTION = 1023;
    public const BUILD_SCHEMAS_EXCEPTION = 1023;
    public const VALIDATE_SCHEMA_EXCEPTION = 1023;

    /**
     * Frmtr
     */
    public const INVALID_BOOLEAN_FORMAT_EXCEPTION = 1023;
    public const SET_BOOLEAN_FORMAT_EXCEPTION = 1023;
    public const FRMTR_TEXT_CENSOR_EXCEPTION = 1023;

    /**
     * Intl exception codes
     */
    public const INVALID_TIMEZONE_EXCEPTION = 1023;
    public const INVALID_UTC_OFFSET = 1023;

    /**
     * Charset Exception
     */
    public const INVALID_CHARSEST_EXCEPTION = 1023;

    /*
     * http validator server exception
     */
    public const HTML_VALIDATOR_SERVER_EXCEPTION = 1023;

    /*
     * Fraction exceptions
     */
    public const INVALID_FRACTION_DENOMINATOR = 1023;

    /*
     * DOMDocument
     */
    public const INVALID_MARKUP_LANGUAGE_EXCEPTION = 1023;
}
