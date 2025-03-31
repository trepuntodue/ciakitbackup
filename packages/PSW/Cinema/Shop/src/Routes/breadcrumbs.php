<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Release.
 */
Breadcrumbs::for('customer.release.index', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('shop::app.customer.account.release.index.page-title'), route('customer.release.index'));
});

Breadcrumbs::for('customer.release.create', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.release.index');

    $trail->push(trans('shop::app.customer.account.release.create.page-title'), route('customer.release.create'));
});

Breadcrumbs::for('customer.release.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('customer.release.index');

    $trail->push(trans('shop::app.customer.account.release.edit.page-title'), route('customer.release.edit', $id));
});