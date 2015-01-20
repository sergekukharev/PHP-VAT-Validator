PHP VAT Validator
=================

About
-----
This is small PHP class that validates VAT number based on simple rules,
without doing any requests to other services.

Currently, validator only validates EU countries with basic rule set.
Some countries, like France, Great Britain, etc., have complex validation which
was skipped here. If you have free time and will to help, feel free to create
pull request.

Project uses PHPUnit test, which are also very basic and cover currently only
main validation method.

All code is styled according to [Zend Coding Standard](http://framework.zend.com/manual/1.12/en/coding-standard.coding-style.html)
Please, use PHP Code Sniffer ([phpcs](https://github.com/squizlabs/PHP_CodeSniffer)) before committing.

If you will notice any bug or would like to see an improvement, please open
an issue.

If you feel like this is not enough, you can try [this project](https://github.com/herdani/vat-validation).

Usage
-----
You can use it with creating a VAT Validator object:

    $vat = 'AA0123456789';
    $country = 'AA';
    $validator = new VatValidator($vat, $country);

    echo $validator->isValid() ? 'Valid!' : 'Invalid!';

or just use static method:

    $vat = 'AA0123456789';
    $country = 'AA';

    echo VatValidator::validate($vat, $country) ? 'Valid!' : 'Invalid!';

You can supply country as 2 or 3 letter ISO code, or even full name.
Also, you can ignore country name parameter. Code will try to get it from VAT
itself, but if it will fail, your VAT is invalid.