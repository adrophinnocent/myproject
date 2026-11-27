<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI crawler policy
    |--------------------------------------------------------------------------
    |
    | Crawlers listed here get an explicit "Allow: /" group in robots.txt. The
    | same private-area rules as the wildcard group are repeated for every
    | entry so an explicit allow can never open up admin, booking or workspace
    | URLs. Regular search engines (Googlebot, Bingbot, ...) keep using the
    | wildcard group and are never blocked.
    |
    | These are allow rules only: no AI crawler is blocked. Change this list
    | (and clear config cache) if the business decides to disallow a crawler.
    |
    */

    'crawlers' => [
        'GPTBot',
        'OAI-SearchBot',
        'ChatGPT-User',
        'ClaudeBot',
        'Claude-User',
        'anthropic-ai',
        'PerplexityBot',
        'Perplexity-User',
        'Google-Extended',
        'Applebot',
        'Applebot-Extended',
        'CCBot',
        'Amazonbot',
        'Bytespider',
        'meta-externalagent',
        'cohere-ai',
        'YouBot',
        'MistralAI-User',
        'Diffbot',
        'omgilibot',
        'ImagesiftBot',
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown representations
    |--------------------------------------------------------------------------
    |
    | Every public content page has a ".md" sibling and can also be requested
    | with Accept: text/markdown. Both are representations of the same page;
    | the HTML URL remains the canonical one.
    |
    */

    'markdown' => [
        'enabled' => env('AI_MARKDOWN_ENABLED', true),
        'negotiation_enabled' => env('AI_MARKDOWN_NEGOTIATION_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP discovery headers
    |--------------------------------------------------------------------------
    */

    'link_headers' => [
        'enabled' => env('AI_LINK_HEADERS_ENABLED', true),
    ],
];
