check-semicolons
================

Quick script to check that JS files ends with a semi-colon.

Sometimes js files (esp minified ones) won't end with a semi-colon. Though this is valid when loaded singularly, in instances where assets are automatically or manually combined (in virtually all mature web framewrosk, for instance assetic in the Symfony2 framework), it can cause syntax errors in the end result.

This script is meant to check over js files and tell you which ones don't end with a semi-colon. It's simple, it helped me, and maybe it'll help someone else. :D
