<template>
  <app-layout title="Dashboard">
    <template #header>
      <div class="flex">
        <div class="mx-auto ml-0">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bulk Import Members
          </h2>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="mt-4">
              <jet-label for="importfile" value="Importfile" />
              <jet-input
                id="importfile"
                type="file"
                class="mt-1 block w-full"
                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                required
                @change="getImportFile"
              />
            </div>

            <div class="mt-4">
              <jet-button> Import </jet-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetButton from "@/Jetstream/Button.vue";

export default {
  name: "BulkImportMembers",

  components: {
    AppLayout,
    JetLabel,
    JetInput,
    JetButton,
  },

  data() {
    return {
      form: this.$inertia.form({
        importfile: null,
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(this.route("bulk-save-members"));
    },
    getImportFile(e) {
      const file = e.target.files[0];
      this.form.importfile = file;
    },
  },
};
</script>
