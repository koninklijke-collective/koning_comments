# koning_comments

Offers a commenting widget for use in extensions.

## Installation

Install extension from TER and include ``Koning Comments`` static template.

## Usage

Usage in Fluid templates: ``<c:widget.comments />``

### Options

- url: link to current page (if left empty, it builds the url based on TSFE->id. Use this param if you use this on a page with an extension with url params
- enableCommenting: use this to make commenting read only (will display previous comments, but no option to post new ones)
- sort: sorting (``ASC`` or ``DESC``)

Example with url:

``<c:widget.comments url="{f:uri.action(action: 'detail', arguments: '{identifier: \'{item.uid}\'}', absolute: 1, noCacheHash: 1)}" />``

### Configuration

``plugin.tx_koningcomments.settings.enableModeration`` (default ``0``): When enabled, comments will be created as ``hidden``.

### Signals

- ``KoninklijkeCollective\KoningComments\ViewHelpers\Widget\Controller\CommentsController``: ``afterCommentCreated``: you can use this signal to send and e-mail or other kind of notification after a comment has been created.

