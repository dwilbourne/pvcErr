=====
Usage
=====

Because messages and codes are all handled separately, throwing an exception is now only concerned with information that
 is relevant in the execution context, i.e. the parameters to the message and any previous Throwable that has occurred.

This is how to throw the exception::

    throw new MyException($param1, $param2, $previous);


There is an examples directory that comes with this library for further help.

