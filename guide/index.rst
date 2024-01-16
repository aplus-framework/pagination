Pagination
==========

.. image:: image.png
    :alt: Aplus Framework Pagination Library

Aplus Framework Pagination Library.

- `Installation`_
- `Getting Started`_
- `Rendering Views`_
- `Custom Language`_
- `URL`_
- `JSON-Encoding`_
- `Conclusion`_

Installation
------------

The installation of this library can be done with Composer:

.. code-block::

    composer require aplus/pagination

Getting Started
---------------

With the Pagination Library it is possible to work with objects of the Pager class.

And a Pager can be instantiated as in the example below:

.. code-block:: php

    use Framework\Pagination\Pager;

    $currentPage = 3;
    $itemsPerPage = 10;
    $totalItems = 230;
    $pager = new Pager($currentPage, $itemsPerPage, $totalItems);

Rendering Views
---------------

You can work with various Pager methods, but the ultimate goal is usually to
render views to be displayed on the page.

The following features are available for rendering views: `Pagination`_,
`HTML Head Links`_, `HTTP Header Link`_, `Front-end Frameworks Support`_,
`Default Rendering View`_, `Custom Views`_.

Pagination
^^^^^^^^^^

The default view name is ``pagination`` and it can be rendered as follows:

.. code-block:: php

    echo $pager->render();

An HTML code similar to this will be displayed:

.. code-block:: html

    <ul class="pagination">
        <li>
            <a rel="prev" href="https://domain.tld/blog/posts?page=2" title="Previous">&laquo;</a>
        </li> 
        <li>
            <a href="https://domain.tld/blog/posts?page=1">1</a>
        </li>
        <li>
            <a href="https://domain.tld/blog/posts?page=2">2</a>
        </li> 
        <li>
            <a rel="canonical" href="https://domain.tld/blog/posts?page=3" class="active">3</a>
        </li>
        <li>
            <a href="https://domain.tld/blog/posts?page=4">4</a>
        </li>
        <li>
            <a href="https://domain.tld/blog/posts?page=5">5</a>
        </li>
        <li>
            <a rel="next" href="https://domain.tld/blog/posts?page=4" title="Next">&raquo;</a>
        </li>
        <li>
            <a href="https://domain.tld/blog/posts?page=23">Last</a>
        </li>
    </ul>

In addition to "full" rendering, it is also possible to render "short views":

.. code-block:: php

    echo $pager->renderShort();

See HTML below. It only has the **previous** and **next** links:

.. code-block:: html

    <ul class="pagination">
        <li>
            <a rel="prev" href="https://domain.tld/blog/posts?page=2" title="Previous">
                &laquo; Previous
            </a>
        </li>
        <li>
            <a rel="next" href="https://domain.tld/blog/posts?page=4" title="Next">
                Next &raquo;
            </a>
        </li>
    </ul>

HTML Head Links
^^^^^^^^^^^^^^^

One way to optimize the indexing of pages visited by web crawlers and also SEO
ranks is to print the pagination links in the ``head`` tag of the HTML page:

.. code-block:: php

    <head>
    <title>Aplus Pagination</title>
    <?= $pager->render('head') ?>
    </head>

Example of rendering links with ``head`` view:

.. code-block:: html

    <head>
    <title>Aplus Pagination</title>
    <link rel="prev" href="https://domain.tld/blog/posts?page=2">
    <link rel="canonical" href="https://domain.tld/blog/posts?page=3">
    <link rel="next" href="https://domain.tld/blog/posts?page=4">
    </head>

HTTP Header Link
^^^^^^^^^^^^^^^^

When working with APIs it may be necessary to paginate the results and for this
there is the `HTTP Link Header <https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Link>`_.

With the Pager defined, it is possible to render the ``header`` view:

.. code-block:: php

    header('Link: ' . $pager->render('header'));

The Link sent header field will look like this:

.. code-block:: http

    Link: <https://domain.tld/blog/posts?page=1>; rel="first",<https://domain.tld/blog/posts?page=2>; rel="prev",<https://domain.tld/blog/posts?page=4>; rel="next",<https://domain.tld/blog/posts?page=23>; rel="last"

Front-end Frameworks Support
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The Aplus Framework Pagination Library works with the following front-end frameworks:

- `Bootstrap <https://getbootstrap.com/>`_
- `Bulma <https://bulma.io/>`_
- `Foundation <https://get.foundation/>`_
- `Materialize <https://materializecss.com/>`_
- `Primer <https://primer.style/>`_
- `Semantic UI <https://semantic-ui.com/>`_
- `Tailwind <https://tailwindcss.com/>`_
- `W3.CSS <https://www.w3schools.com/w3css/default.asp/>`_

Note that it is necessary to load links from CSS files.

See an example using Bootstrap:

- Insert the link tag with the CSS file.

.. code-block:: html

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">

- Render pagination using the ``bootstrap`` view:

.. code-block:: php

    echo $pager->render('bootstrap');

The result will be like the image below:

.. image:: img/bootstrap.png
    :alt: Aplus Pagination - Bootstrap View

It is also possible to render the "short view". Note the view name suffixed with ``-short``:

.. code-block:: php

    echo $pager->render('bootstrap-short');

And the result:

.. image:: img/bootstrap-short.png
    :alt: Aplus Pagination - Bootstrap Short View

Default Rendering View
^^^^^^^^^^^^^^^^^^^^^^

You can always render views by passing their name in the render method.

The most common is that an application works with only one pagination style and
for that it is possible to set the default view.

Once this is done, the Pager's ``render`` method can be called with no arguments
and the default view will be rendered.

See an example setting the ``bulma`` view to default:

.. code-block:: php

    $pager->setDefaultView('bulma');

And the call to render:

.. code-block:: php

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.2/css/bulma.min.css">
    <?= $pager->render() ?>

And the result in the web browser:

.. image:: img/bulma.png
    :alt: Aplus Pagination - Bulma View

The default view will also be used by the ``renderShort`` method

.. code-block:: php

    echo $pager->renderShort();

which will render the "short view":

.. image:: img/bulma-short.png
    :alt: Aplus Pagination - Bulma Short View

Custom Views
^^^^^^^^^^^^

If you need to use a different view style, add the view name and filepath:

.. code-block:: php

    $name = 'my-pager';
    $filepath = __DIR__ . '/Views/my-pager.php';
    $pager->setView($name, $filepath);

And then you can render it:

.. code-block:: php

    echo $pager->render('my-pager');

Note that it is possible to set the default view and call the ``render`` method
with no arguments.

Custom Language
---------------

The default language used is English.

To set a different language, do this in the Pager constructor:

.. code-block:: php

    $language = new Framework\Language\Language('es');
    $pager = new Pager($currentPage, $itemsPerPage, $totalItems, $language);

Or when needed via the ``setLanguage`` method:

.. code-block:: php

    $pager->setLanguage($language);

After setting the language, it is possible to render the pagination.

.. code-block:: php

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <?= $pager->render('semantic-ui') ?>

Example using Semantic UI with Spanish language:

.. image:: img/semantic-ui.png
    :alt: Aplus Pagination - Semantic UI View

If the Pagination Library is not localized in your language, you can contribute by adding
it with a `Pull Request <https://github.com/aplus-framework/pagination/pulls>`_.

It is also possible to add custom languages at runtime. See the
`Language Library <https://gitlab.com/aplus-framework/libraries/language>`_ to know more.

URL
---

The URL used by the Pager is obtained through the HTTP request.

In some cases it is necessary to generate pagination for other resources or,
also, when working from the command line.

Then the URL can be passed into the constructor:

.. code-block:: php

    $url = 'https://domain.tld/blog/posts';
    $pager = new Pager($currentPage, $itemsPerPage, $totalItems, url: $url);

Or whenever you want via the ``setUrl`` method:

.. code-block:: php

    $pager->setUrl($url);

JSON-Encoding
-------------

Nowadays, it is very common to use JSON to work with interactions through AJAX or APIs.

An application can respond the pagination links via the `HTTP Header Link`_.
However, it is also an option to put the links next to the message content.

Having a Pager object instantiated, just put it in to be encoded:

.. code-block:: php

    $contents = [
        'data' => [],
        'links' => $pager,
    ];
    echo json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

And the result will be similar to this:

.. code-block:: json

    {
        "data": [],
        "links": {
            "self": "https://domain.tld/blog/posts?page=3",
            "first": "https://domain.tld/blog/posts?page=1",
            "prev": "https://domain.tld/blog/posts?page=2",
            "next": "https://domain.tld/blog/posts?page=4",
            "last": "https://domain.tld/blog/posts?page=23"
        }
    }

Conclusion
----------

Aplus Pagination Library is an easy-to-use tool for PHP developers, beginners and experienced. 
It's perfect for building full-featured pagination in a very simple way. 
The more you use it, the more you will learn.

.. note::
    Did you find something wrong? 
    Be sure to let us know about it with an
    `issue <https://gitlab.com/aplus-framework/libraries/pagination/issues>`_. 
    Thank you!

