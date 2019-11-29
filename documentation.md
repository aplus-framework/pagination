# Pagination Library *documentation*

Pagination are links that link one page to another.
These links can be either in HTML as in the headers.

```php
use Framework\Pagination\Pager;
$current_page = 1;
$items_per_page = 10;
$total_items = 100; // Total items in database
$items = []; // Items coming from the database, 10 items
$pager = new Pager($current_page, $items_per_page, $total_items, $items);
// Sets pagination URL and allowed queries on links
$pager->setURL('http://domain.tld', ['order']); // The "page" query is automatic
```

After instantiating and setting the pagination URL, the view can be called like this:

```php
echo $pager->render();
```

or with a custom view:

```php
header('Link: ' . $pager->render('header'));
```

The 5 native views are:

- **head**, used in pagination inside the head tag;
- **header**, used to page through HTTP Link header;
- **pager**, a simple pager with only Back and Next links;
- **pagination**, the default pager showing numbers and direction buttons;
- **bootstrap4**, similar to *pagination*, but customized for the Bootstrap 4 framework.

To add a custom view just add the name and path as well:

```php
$pager->setView('custom', 'path/to/file.php');
```

and then call:

```php
echo $pager->render('custom');
```
