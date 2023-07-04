=====
Setup
=====

1. Decide where you want to keep your exception code prefixes file.  If you are using a framework that has a config
directory, that would be a sensible choice, or simply perhaps in the root of your src directory.

2. as part of bootstrapping your application, use putenv() to set "XCodePrefixes" to the filepath chosen in step 1.

Update this file as often as you create a new exception library.  Recall that creating an exception library
consists of

* creating the directory in which the exceptions live.
* creating a class inside that directory which extends pvc's XDataAbstract class and stores messages and "local" codes.
* create exceptions where the constructor consists of the parameter(s) to the message followed by any previous exception you are adding to the exception stack.

