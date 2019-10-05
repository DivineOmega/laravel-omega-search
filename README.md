# Laravel Omega Search

Omega Search allows you to easily add an intelligent search engine to your Laravel powered website or web application. 
It can be configured to search any of your Eloquent models.

Under the hood, this uses the [Omega Search](https://github.com/DivineOmega/omega-search) package.

## Installation

You can install this package with Composer.

```
composer require divineomega/laravel-omega-search
```

## Usage

To use Laravel Omega Search, first add the `OmegaSearchTrait` 
to the models you wish to search. You must then implement 
the following two abstract methods.

* `getOmegaSearchFieldsToSearch()` - Must return an array of the model's fields to search.
* `getOmegaSearchConditions()` - Must return an associative array of the search conditions. Example: `['active' => 1, 'discontinued' => 0]`

After this setup, a search can be performed by calling
the static `omegaSearch($searchText)` method on the model.
This method performs an intelligent fuzzy search, and returns 
a query builder filtered to the related records in descending 
order of relevance.

The related models can then be retrieved (`->get()`) or 
paginated (`->paginate()`) as required.
