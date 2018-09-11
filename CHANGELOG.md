## [5.4.2](https://github.com/zerospam/sdk-framework/compare/v5.4.1...v5.4.2) (2018-09-11)


### Performance Improvements

* **RefreshToken:** Add number of tries into callbacks ([c644b40](https://github.com/zerospam/sdk-framework/commit/c644b40))

## [5.4.1](https://github.com/zerospam/sdk-framework/compare/v5.4.0...v5.4.1) (2018-09-11)


### Bug Fixes

* **Middleware:** Fixes return value of MiddlewareClient Trait ([9c948d8](https://github.com/zerospam/sdk-framework/commit/9c948d8))
* **Middleware:** Remove Middleware client trait ([11abebf](https://github.com/zerospam/sdk-framework/commit/11abebf))

# [5.4.0](https://github.com/zerospam/sdk-framework/compare/v5.3.2...v5.4.0) (2018-09-11)


### Features

* **RefreshToken:** Add refreshToken middleware ([e144740](https://github.com/zerospam/sdk-framework/commit/e144740))

## [5.3.2](https://github.com/zerospam/sdk-framework/compare/v5.3.1...v5.3.2) (2018-08-14)


### Bug Fixes

* **Enums:** Adjusted return type ([4c5bd8f](https://github.com/zerospam/sdk-framework/commit/4c5bd8f))

## [5.3.1](https://github.com/zerospam/sdk-framework/compare/v5.3.0...v5.3.1) (2018-08-14)


### Bug Fixes

* **Enums:** Removed return type enforcement ([dfab545](https://github.com/zerospam/sdk-framework/commit/dfab545))

# [5.3.0](https://github.com/zerospam/sdk-framework/compare/v5.2.2...v5.3.0) (2018-08-14)


### Features

* **Enums:** Added Enums Insensitive contract ([095f6e3](https://github.com/zerospam/sdk-framework/commit/095f6e3))

## [5.2.2](https://github.com/zerospam/sdk-framework/compare/v5.2.1...v5.2.2) (2018-08-06)


### Bug Fixes

* **deps:** Fix problem with Carbon deps and laravel ([e8cd656](https://github.com/zerospam/sdk-framework/commit/e8cd656))

## [5.2.1](https://github.com/zerospam/sdk-framework/compare/v5.2.0...v5.2.1) (2018-07-20)


### Bug Fixes

* **Middleware:** Change middleware client accessibility ([2fc1d40](https://github.com/zerospam/sdk-framework/commit/2fc1d40))

# [5.2.0](https://github.com/zerospam/sdk-framework/compare/v5.1.0...v5.2.0) (2018-07-16)


### Bug Fixes

* **EmptyResponse:** Set the RateLimit only for BaseResponse ([02c99ca](https://github.com/zerospam/sdk-framework/commit/02c99ca))


### Features

* **Response:** Split Ratelimited from BaseResponse ([290a97d](https://github.com/zerospam/sdk-framework/commit/290a97d))

# [5.1.0](https://github.com/zerospam/sdk-framework/compare/v5.0.0...v5.1.0) (2018-07-16)


### Features

* **Request:** Adds empty request ([48059fd](https://github.com/zerospam/sdk-framework/commit/48059fd))
* **Request:** Improves the EmptyResponse ([dec801e](https://github.com/zerospam/sdk-framework/commit/dec801e))

# [5.0.0](https://github.com/zerospam/sdk-framework/compare/v4.0.1...v5.0.0) (2018-07-12)


### Bug Fixes

* **Request:** Don't send nullableChanged ([af1a056](https://github.com/zerospam/sdk-framework/commit/af1a056))


### Features

* **Request:** Add support for nullable fields ([8a36959](https://github.com/zerospam/sdk-framework/commit/8a36959))
* **Request:** Add support for NullableFields ([34c45bc](https://github.com/zerospam/sdk-framework/commit/34c45bc))


### BREAKING CHANGES

* **Request:** Null fields won't be sent to the API anymore if not set as nullable fields

## [4.0.1](https://github.com/zerospam/sdk-framework/compare/v4.0.0...v4.0.1) (2018-07-11)


### Bug Fixes

* **Tests:** Validation of request ([2b894a0](https://github.com/zerospam/sdk-framework/commit/2b894a0))

# [4.0.0](https://github.com/zerospam/sdk-framework/compare/v3.0.0...v4.0.0) (2018-06-20)


### Features

* **Collection:** Collections iterator ([acbc67f](https://github.com/zerospam/sdk-framework/commit/acbc67f))
* **Collection:** Improves Collections ([7693d2c](https://github.com/zerospam/sdk-framework/commit/7693d2c))


### BREAKING CHANGES

* **Collection:** dataToResponses renamed to dataToResponse and return a IResponse instead of an
Array

# [3.0.0](https://github.com/zerospam/sdk-framework/compare/v2.3.0...v3.0.0) (2018-06-20)


### Features

* **Bindable:** Makes bindable a trait ([1594122](https://github.com/zerospam/sdk-framework/commit/1594122))


### BREAKING CHANGES

* **Bindable:** BindableRequest is not a trait IsBindable instead of a Class.

# [2.3.0](https://github.com/zerospam/sdk-framework/compare/v2.2.0...v2.3.0) (2018-06-20)


### Features

* **collection:** Add collection response ([8793884](https://github.com/zerospam/sdk-framework/commit/8793884))
* **Collection:** Make collection response usable ([2a80f05](https://github.com/zerospam/sdk-framework/commit/2a80f05))


### Performance Improvements

* **Collection:** Makes Collection response iterable ([ab0c21a](https://github.com/zerospam/sdk-framework/commit/ab0c21a))

# [2.2.0](https://github.com/zerospam/sdk-framework/compare/v2.1.0...v2.2.0) (2018-06-11)


### Features

* **middleware:** Interceptor of Request ([38bc5e5](https://github.com/zerospam/sdk-framework/commit/38bc5e5))

# [2.1.0](https://github.com/zerospam/sdk-framework/compare/v2.0.1...v2.1.0) (2018-06-11)


### Bug Fixes

* **Arguments:** Make argCollector Arrayable ([2e0adb4](https://github.com/zerospam/sdk-framework/commit/2e0adb4))


### Features

* **Arguments:** Add sub keyed stackable argument ([42a9150](https://github.com/zerospam/sdk-framework/commit/42a9150))


### Performance Improvements

* **Arguments:** Don't generate stack key if not needed ([8523c25](https://github.com/zerospam/sdk-framework/commit/8523c25))

## [2.0.1](https://github.com/zerospam/sdk-framework/compare/v2.0.0...v2.0.1) (2018-06-11)


### Bug Fixes

* **tests:** Make base of tests available to other libs ([8838ccd](https://github.com/zerospam/sdk-framework/commit/8838ccd))

# [2.0.0](https://github.com/zerospam/sdk-framework/compare/v1.0.0...v2.0.0) (2018-06-08)


### Bug Fixes

* **client:** Force client to generate request ([949926e](https://github.com/zerospam/sdk-framework/commit/949926e))
* **test:** Fix the wrong key used ([093f135](https://github.com/zerospam/sdk-framework/commit/093f135))


### Features

* **args:** Adds stackable arguments ([5c519fe](https://github.com/zerospam/sdk-framework/commit/5c519fe))
* **argument:** Change how glue is defined ([bab9395](https://github.com/zerospam/sdk-framework/commit/bab9395))


### BREAKING CHANGES

* **argument:** IMergeableArgument::glue() is now static.

# 1.0.0 (2018-06-06)


### Bug Fixes

* **package:** Fix name of npm package ([ecd3e62](https://github.com/zerospam/sdk-framework/commit/ecd3e62))
* **travis:** fix github url ([3e86e16](https://github.com/zerospam/sdk-framework/commit/3e86e16))
* **travis:** Fix travis config ([b6a7348](https://github.com/zerospam/sdk-framework/commit/b6a7348))


### Features

* **lib:** First version ([84e1a0f](https://github.com/zerospam/sdk-framework/commit/84e1a0f))


### BREAKING CHANGES

* **lib:** First version of the SDK
