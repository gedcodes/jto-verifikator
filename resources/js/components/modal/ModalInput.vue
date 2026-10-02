<script>
    import { XCircleIcon } from "@heroicons/vue/outline";

    export default {
        name: 'ModalInput',
        methods: {
            close() {
                this.$emit('close');
            },
        },
        components: {
            XCircleIcon,
        },
    };
</script>

<template>
    <Transition name="modal">
        <div class="modal-overlay">
        <div class="modal-freq">
            <div class="modal">
                <div
                    class="modal-header"
                    id="modalTitle"
                >
                    <slot name="header">
                        this is header
                    </slot>
                </div>
                <section
                    class="modal-body"
                    id="modalDescription"
                >
                    <slot name="body">
                        this is body
                    </slot>
                </section>
            </div>
        </div><div class="close">
            <XCircleIcon class="close-img" @click="close" />
        </div>
    </div>
    </Transition>
</template>

<style scoped>

.modal-overlay {
  position: fixed;
  z-index: 9998;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  background-color: #000000d5;
  transition: opacity 0.3s ease;
}

.modal-freq {
    justify-content: center;
    align-items: center;
    margin-top: 5%;
}

.modal {
  background-color: white;
  width: 600px;
  padding: 20px;
  border-radius: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}
.close {
  margin: 5% 0 0 16px;
}

.close-img {
  width: 30px;
  color: #ac003e;
  cursor: pointer;
}

.check {
  width: 150px;
}

.modal-header {
  font-weight: 500;
  font-size: 28px;
  margin-bottom: 10px;
  position: relative;
  text-align: center;
  border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
}

.modal-body {
    position: relative;
    padding: 10px 10px;
}

.modal-footer {
    text-align: right;
}

button {
  background-color: #009018;
  width: 150px;
  height: 40px;
  color: white;
  font-size: 14px;
  border-radius: 16px;
  margin-top: 20px;
}

.modal-enter-from {
  opacity: 0;
}

.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
