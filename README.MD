PvcErr contains two sorts of exceptions.  The first are "rebrandings" of stock php exceptions.  The second are a set of generic exceptions that are similar to the stock exceptions in php.

Both of these sets of exceptions have been modified from the traditional construction signature of an exception (new Exception(string, int, Throwable)) to use a pvcMsg object as the first argument.  See the pvcMsg repository for more information on the flexibility that this provides.
