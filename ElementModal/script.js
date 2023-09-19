import { buildRefs } from '@/assets/scripts/helpers'

export default function (el) {
    const ref = buildRefs(el)
    const shouldCloseOnClickOutside = el.getAttribute('data-close-on-click-outside') === 'true'

    ref.modalTrigger.addEventListener('click', openModal)

    function openModal() {
        ref.modal.showModal()
        shouldCloseOnClickOutside && ref.modal.addEventListener('click', closeDialogOnClickOutside)
    }

    function closeModal() {
        ref.modal.close()
        ref.modal.removeEventListener('click', closeDialogOnClickOutside)
    }

    function closeDialogOnClickOutside(e) {
        e.target === ref.modal && closeModal()
    }
}
