# Yii Proxy Change Log

## 1.1.0 under development

- Chg #75: Change PHP constraint in `composer.json` to `8.1 - 8.4` (@batyrmastyr)
- Bug #75: Fix the nullable parameter declarations for compatibility with PHP 8.4 (@batyrmastyr)
- Enh #75: Support disjunctive normal form types (@batyrmastyr)

## 1.0.5 January 17, 2023

- Bug #67: Fix unexpected warning in `ClassCache::get()` in some cases (@vjik)

## 1.0.4 August 16, 2022

- Bug #64: Unfinalize `ObjectProxy::__construct()` (@vjik)

## 1.0.3 August 15, 2022

- Bug #59: Fix rendering nullable union types (@vjik)
- Bug #62: Fix rendering intersection types (@vjik)
- Bug #63: Finalize `ObjectProxy::__construct()` (@vjik)

## 1.0.2 July 18, 2022

- Bug #58: Fix rendering of class modifiers (@arogachev)

## 1.0.1 July 11, 2022

- Bug #54: Revert `implements` section for proxy class (@arogachev)

## 1.0.0 July 09, 2022

- Initial release.
