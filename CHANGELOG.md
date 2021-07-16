# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0]

* Add fallback for owner class without getSortableScope defined
* Set Sort field to 1 when scope list is otherwise empty
* Automatically exclude the current object from the scope list, when it is already in the DB. (Previously needed to be applied manually in class::getSortableScope)

## [1.0.0]

Initial release
