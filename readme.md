# Pipit Cloudinary - Template Filter

Pipit Cloudinary is a Perch [template filter](https://docs.grabaperch.com/api/template-filters/) that enables you to easily fetch your images to Cloudinary on the fly. Cloudinary also gives you the option to manipulate your images with dynamic URLs.


## Installation
- Download the latest version of the Pipit Cloudinary.
- Unzip the download
- Place the `PipitTemplateFilter_cloudinary.class.php` file in the folder `perch/addons/templates/filters/`
- Include the class in the file `perch/addons/templates/filters.php`:

```php
include('filters/PipitTemplateFilter_cloudinary.class.php');
```



## Configuration

### Perch

- Add your site URL in the Settings
- In your config file `perch/config/config.php` add your Cloudinary cloud name:

```php
define('CLOUDINARY_CLOUDNAME', 'your-cloud-name');
```

You also need to enable template filters in your config:

```php
define('PERCH_TEMPLATE_FILTERS', true);
```


### Cloudinary

- Log into your account and go to **Settings > Security**
- Under **Restricted image types**, make sure "Fetched URL" is unchecked
- Under **Allowed fetch domains** list the domain(s) you want to fetch images from


## Environments
The filter by default does not rewrite your image URLs if you set your Perch production environment to development:

```php
define('PERCH_PRODUCTION_MODE', PERCH_DEVELOPMENT);
```

This is because Cloudinary requires publicly accessible URL to fetch the images from.



## Usage

To use the cloudinary filter add the attribute `filter="cloudinary"` to your tag:

```markup
<perch:content id="image" type="image" filter="cloudinary">
```

To add image manipulation and compression options use the `opts` attribute:

```markup
<perch:content id="image" type="image" filter="cloudinary" opts="w_800,h_600,f_auto">
```

### External links

You can also use it on external links (in a regular text field for example) by adding the `external` attribute.

```markup
<perch:content id="image_url" type="text" filter="cloudinary" external>
```

Adding the `external` attribute tells the filter not to add your site domain.