
.. toctree::
   :hidden:

   index
   discussion
   install
   setup
   usage

===============
pvcErr Overview
===============

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



