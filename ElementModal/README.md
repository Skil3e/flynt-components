# Element Modal

A modal component with optional header and footer. This component encapsulates the functionality for open close and close on click outside.

*When passing the **trigger** element to the ```renderComponent``` function make sure you add the ```data-ref='modalTrigger'``` attribute to the trigger element.*

## Usage

```html
{% set modalTrigger %}
  <button class="button" data-ref="modalTrigger">
    Open modal
  </button>
{% endset %}

{% set modalHeader %}
  <p>Modal Header</p>
{% endset %}

{% set modalBody %}
  <p>This is the body of the modal</p>
{% endset %}

{% set modalFooter %}
  <p>Modal footer</p>
{% endset %}

{{ renderComponent('ElementModal', {
  trigger: modalTrigger,
  header: modalHeader,
  body: modalBody,
  footer: modalFooter
}) }}
```
