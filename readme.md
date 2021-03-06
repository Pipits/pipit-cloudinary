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

### Cloudinary

- Log into your account and go to **Settings > Security**
- Under **Restricted image types**, make sure "Fetched URL" is unchecked
- Under **Allowed fetch domains** list the domain(s) you want to fetch images from


### Perch

#### Enable template filters

You also need to enable template filters in your config file `perch/config/config.php`:

```php
define('PERCH_TEMPLATE_FILTERS', true);
```


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


#### Development / Staging environments

By default, the filter does not rewrite your image URLs if you set your Perch production environment to development or staging:
 
```php
define('PERCH_PRODUCTION_MODE', PERCH_DEVELOPMENT);
```

You have the option to enable it:

```php
define('PIPIT_CLOUDINARY_DEV', true);
```

Note that Cloudinary requires a publicly accessible URL to fetch the image from. So if you enable it on a local development environment for example, it won't work.





## Usage

To use the cloudinary filter add the attribute `filter="cloudinary"` to your tag:

```html
<perch:content id="image" type="image" filter="cloudinary">
```

To add image manipulation and compression options use the `opts` attribute:

```html
<perch:content id="image" type="image" filter="cloudinary" opts="w_800,h_600,f_auto">
```

### Dynamic variables

You can also use dynamic variables inside the `opts` attribute. These are the variables you have access to inside the template:

```html
<perch:content id="width" type="select" label="Image Width" options="200|w_200,400|w_400,600|w_600" suppress>
<perch:content id="height" type="select" label="Image Height" options="200|h_200,400|h_400,600|h_600" suppress>
<perch:content id="image" type="image" label="Image" filter="cloudinary" opts="f_auto,{width},{height}">
```


### External links [DEPRECATED]

**⚠️This option is available on v1.0. Newer versions handles this automatically.**

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



## Upload URLs

While the filter was with Cloudinary's fetch URLs in mind, there are cases where it is benefitial to programatically upload images directly to Cloudinary and only store the image URL in a regular text field in Perch. The template filter can handle these URLs as long as it uses the same Cloudinary cloud name as the one you've set in your config file.


## Helpful References:

- [Image Transformation](https://cloudinary.com/documentation/image_transformations)
- [Image Optimization](https://cloudinary.com/documentation/image_optimization)
- [Cloudinary Cookbook](https://cloudinary.com/cookbook)