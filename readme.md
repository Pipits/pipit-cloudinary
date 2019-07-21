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

#### Site URL

Add your site URL in your config file `perch/config/config.php`:

```php
define('SITE_URL', 'https://example.com');
```

If you don't define `SITE_URL`, the URL from Perch Settings will be used as a fallback.


#### Cloudinary cloud name

In your config file `perch/config/config.php` add your Cloudinary cloud name:

```php
define('CLOUDINARY_CLOUDNAME', 'your-cloud-name');
```

#### Enable template filters

You also need to enable template filters in your config:

```php
define('PERCH_TEMPLATE_FILTERS', true);
```


#### Development / Staging environments

By default the filter is not enable on development or staging environments. That is when you set `PERCH_PRODUCTION_MODE` to `PERCH_DEVELOPMENT` or `PERCH_STAGING`.

You have the option to enable it:

```php
define('PIPIT_CLOUDINARY_DEV', true);
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

```html
<perch:content id="image" type="image" filter="cloudinary">
```

To add image manipulation and compression options use the `opts` attribute:

```html
<perch:content id="image" type="image" filter="cloudinary" opts="w_800,h_600,f_auto">
```


### External links [DEPRECATED]

**This option is available on v1.0. Newer versions handles this automatically.**

You can also use it on external links (in a regular text field for example) by adding the `external` attribute.

```html
<perch:content id="image_url" type="text" filter="cloudinary" external>
```

Adding the `external` attribute tells the filter not to add your site domain.




## Image Manipulation/Transformation
You can do a lot with Cloudinary that it is best to refer to their documentation for the full options. Here are some common ones:

| Option | Description | Examples                               |
|--------|-------------| ---------------------------------------|
| `w_`   | Width       | `w_800`                                |
| `h_`   | Height      | `h_400`                                |
| `q_`   | Quality     | `q_80`, `q_auto`, `q_auto:good`        |
| `f_`   | Format      | `f_auto`                               |
| `c_`   | Crop        | `c_fill`, `c_fit`, `c_limit`, `c_crop` |


Using `f_auto` tells Cloudinary to serve the best file format for the browser. So on Chrome, for example, it will server a JPG as a WebP since the format is supported in Chrome and has a smaller file size.

Using `c_limit` allows you to limit the image dimensions. For instance, `w_1000,c_limit` scales the image down if it is larger than 1000 in width, but will not scale it up if it is already smaller.

Using `c_fill` fills the image in a given dimensions similar to CSS's `object-fit: cover` and `background-size: cover`. Example usage: `w_800,h_600,c_fill`.

On the other hand `c_fit` is like CSS's `object-fit: contain` and `background-size: contain` as it resizes the image to take up as much space as possible in a given dimension while maintaining the aspect ratio and keeping the whole image visible. Example usage:  `w_800,h_600,c_fit`.


## Helpful References:

- [Image Transformation](https://cloudinary.com/documentation/image_transformations)
- [Image Optimization](https://cloudinary.com/documentation/image_optimization)
- [Cloudinary Cookbook](https://cloudinary.com/cookbook)