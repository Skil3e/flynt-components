<?php

namespace Flynt\Components\GridPostsFilters;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

/*
 * Example on how to filters posts by tags.
 * The action bellow can also be used to create custom query params
 * for custom taxonomies, custom post types, etc.
*/
add_action('pre_get_posts', function ($query) {
    if ($query->is_main_query() && !is_admin()) {
        $tags = $_GET['tags'] ?? null;
        $query->set('tag__in', $tags);
    }
});

add_filter('Flynt/addComponentData?name=GridPostsFilters', function ($data) {
    // Set the taxonomies to filter by
    $taxonomies = ['category', 'post_tag'];

    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
        ]);

        // Map taxonomy to the appropriate query parameter
        $queryParam = $taxonomy;
        switch ($taxonomy) {
            case 'category':
                $queryParam = 'cat';
                break;
            case 'post_tag':
                $queryParam = 'tags';
                break;
        }

        // Get the taxonomy label
        $labels = get_taxonomy_labels(get_taxonomy($taxonomy));
        $taxLabel = $labels->name;

        // Set the taxonomy label and query parameter
        $data['taxonomies'][$taxonomy] = [
            'label' => $taxLabel,
            'param' => $queryParam,
        ];

        // Get the selected terms from the url
        $queryVar = $_GET[$queryParam] ?? null;
        // If the query parameter is an array, use it as is
        $selected = empty($queryVar)
            ? []
            : (is_array($queryVar)
                ? $queryVar
                : explode(',', $queryVar));

        // Set selected value to selected terms
        if (count($terms) > 0) {
            $data['taxonomies'][$taxonomy]['terms'] = array_map(function ($term) use ($selected) {
                $timberTerm = Timber::get_term($term);
                $timberTerm->selected = in_array($term->term_id, $selected);
                return $timberTerm;
            }, $terms);
        }
    }

    // Get current order from the url
    $data["order"] = $_GET['order'] ?? 'DESC';

    $queriedObject = get_queried_object();
    if (is_home()) {
        $data['isHome'] = true;
        $data['title'] = $queriedObject->post_title ?? get_bloginfo('name');
    } else {
        $data['title'] = get_the_archive_title();
        $data['description'] = get_the_archive_description();
    }

    return $data;
});


Options::addTranslatable('GridPostsFilters', [
    [
        'label'     => __('Content', 'flynt'),
        'name'      => 'general',
        'type'      => 'tab',
        'placement' => 'top',
        'endpoint'  => 0,
    ],
    [
        'label'        => __('Title', 'flynt'),
        'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
        'name'         => 'preContentHtml',
        'type'         => 'wysiwyg',
        'tabs'         => 'visual,text',
        'media_upload' => 0,
        'delay'        => 0,
    ],
    [
        'label'     => __('Labels', 'flynt'),
        'name'      => 'labelsTab',
        'type'      => 'tab',
        'placement' => 'top',
        'endpoint'  => 0
    ],
    [
        'label'      => '',
        'name'       => 'labels',
        'type'       => 'group',
        'sub_fields' => [
            [
                'label'         => __('Filter by', 'flynt'),
                'name'          => 'filterBy',
                'type'          => 'text',
                'default_value' => __('Filter by', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => '50',
                ],
            ],
            [
                'label'         => __('Previous', 'flynt'),
                'name'          => 'previous',
                'type'          => 'text',
                'default_value' => __('Prev', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => '50',
                ],
            ],
            [
                'label'         => __('Next', 'flynt'),
                'name'          => 'next',
                'type'          => 'text',
                'default_value' => __('Next', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => '50',
                ],
            ],
            [
                'label'         => __('Load More', 'flynt'),
                'name'          => 'loadMore',
                'type'          => 'text',
                'default_value' => __('Load More', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => '50',
                ],
            ],
            [
                'label'         => __('No Posts Found Text', 'flynt'),
                'name'          => 'noPostsFound',
                'type'          => 'text',
                'default_value' => __('No post found.', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => '50',
                ],
            ],
            [
                'label'         => __('All Posts', 'flynt'),
                'name'          => 'allPosts',
                'type'          => 'text',
                'default_value' => __('All', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => '50',
                ],
            ],
            [
                'label'         => __('Reading Time - (20) min read', 'flynt'),
                'instructions'  => __('%d is placeholder for number of minutes', 'flynt'),
                'name'          => 'readingTime',
                'type'          => 'text',
                'default_value' => __('%d min read', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => 50
                ],
            ],
            [
                'label'         => __('Read More', 'flynt'),
                'name'          => 'readMore',
                'type'          => 'text',
                'default_value' => __('Read More', 'flynt'),
                'required'      => 1,
                'wrapper'       => [
                    'width' => '50',
                ],
            ]
        ],
    ],
    [
        'label'     => __('Options', 'flynt'),
        'name'      => 'optionsTab',
        'type'      => 'tab',
        'placement' => 'top',
        'endpoint'  => 0
    ],
    [
        'label'      => '',
        'name'       => 'options',
        'type'       => 'group',
        'layout'     => 'row',
        'sub_fields' => [
            FieldVariables\getTheme()
        ]
    ],
]);

