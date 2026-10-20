# Dynamic Options plugin for Craft CMS

A fieldtype that renders a dropdown based on twig code.

## Requirements

This plugin requires Craft CMS 5.0.0 or later and PHP 8.2 or later.

For Craft 4, use version 2.x.

## Installation

To install the plugin, follow these instructions.

1.  Open your terminal and go to your Craft project:

        cd /path/to/project

2.  Then tell Composer to load the plugin:

        composer require webdna/dynamic-options

3.  In the Control Panel, go to Settings → Plugins and click the “Install” button for Dynamic Options.

## Dynamic Options Overview

Dynamic Options give you the ability to populate a dropdown field type with option generated from twig code.

Two field types are provided:

- **Dynamic Options** — a dropdown; its value is the chosen option’s value (a string), or `null`.
- **Dynamic Multi Options** — a multi-select; its value is an array of the chosen values.

## Configuring Dynamic Options

Write twig code or use twig to include an external twig file that renders json. The twig must output the options as comma-separated JSON objects, each with a `label` and a `value`; the field wraps them in `[…]` itself. `element` is the element being edited (it can be `null`).

    {% for entry in craft.entries.section('news').all() %}
        {"label": "{{ entry.title|e('js') }}", "value": "{{ entry.id }}"}{{ not loop.last ? ',' }}
    {% endfor %}

The twig is rendered in site template mode, so `{% include %}` resolves against your `templates/` folder.

If the twig throws or does not output valid JSON, the field shows an error in place of its input (and logs why) rather than breaking the edit page. A saved value that is no longer among the options is kept and shown as its raw value, so saving the element does not silently change it.

Brought to you by [webdna](https://webdna.co.uk)
