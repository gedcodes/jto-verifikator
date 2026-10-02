<template>
  <Form @submit="onSubmit">
    <div
      v-for="{ as, name, label, children, ...attrs } in schema"
      :key="name"
    >
        <label class="block mt-4">
            <span
                v-if="label"
                class="after:content-['*'] after:ml-0.5 after:text-red-500 block text-sm font-medium text-slate-700"
                :for="name"
            >
                {{ label }}
            </span>
            <Field
                :as="as"
                :id="name"
                :name="name"
                v-bind="attrs"
                class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1"

            >
                <template v-if="children && children.length">
                    <component
                        v-for="({ tag, text, ...childAttrs }, idx) in children"
                        :key="idx"
                        :is="tag"
                        v-bind="childAttrs"
                    >
                        {{ text }}
                    </component>
                </template>
            </Field>
            <ErrorMessage :name="name" class="mt-2 text-red-700 font-light text-sm" />
        </label>
    </div>

    <button>Submit</button>
  </Form>
</template>

<script>
import { Form, Field, ErrorMessage } from 'vee-validate';

export default {
    name: 'DynamicForm',
    components: {
        Form,
        Field,
        ErrorMessage,
    },
    props: {
        schema: {
        type: Object,
        required: true,
        },
    },
    methods: {
        onSubmit(values) {
            console.log(values);
        }
    }
};
</script>
