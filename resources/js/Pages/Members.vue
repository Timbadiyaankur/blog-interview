<template>
  <app-layout title="Dashboard">
    <template #header>
      <div class="flex">
        <div class="mx-auto ml-0">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Members
          </h2>
        </div>
        <div class="mx-auto mr-0">
          <a
            :href="route('add-member')"
            class="
              bg-gray-400
              hover:bg-gray-500
              transition-colors
              rounded-[8px]
              px-[15px]
              py-[4px]
              text-white
              focus:ring-2
              ring-yellow-500
            "
            >Add Member</a
          >
        </div>
        <div class="mx-auto mr-0 ml-1" style="cursor: pointer">
          <a
            :href="route('export-members')"
            class="
              bg-green-400
              hover:bg-green-500
              transition-colors
              rounded-[8px]
              px-[15px]
              py-[4px]
              text-white
              focus:ring-2
              ring-yellow-500
            "
            >Export</a
          >
        </div>
        <div class="mx-auto mr-0 ml-1" style="cursor: pointer">
          <a
            :href="route('import-members')"
            class="
              bg-blue-400
              hover:bg-blue-500
              transition-colors
              rounded-[8px]
              px-[15px]
              py-[4px]
              text-white
              focus:ring-2
              ring-yellow-500
            "
            >Import</a
          >
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div
            class="flex items-center justify-center font-sans overflow-hidden"
          >
            <div class="w-full lg:w-5/6">
              <div class="bg-white shadow-md rounded my-6">
                <!-- Table -->
                <div class="p-3">
                  <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                      <thead
                        class="
                          text-xs
                          font-semibold
                          uppercase
                          text-gray-400
                          bg-gray-50
                        "
                      >
                        <tr>
                          <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-left">Name</div>
                          </th>
                          <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-left">Email</div>
                          </th>
                          <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-left">Created</div>
                          </th>
                          <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Action</div>
                          </th>
                        </tr>
                      </thead>
                      <tbody class="text-sm divide-y divide-gray-100">
                        <tr v-for="member in members">
                          <td class="p-2 whitespace-nowrap">
                            <div class="flex items-center">
                              <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3">
                                <img
                                  v-if="member.avatar"
                                  class="w-10 h-10 rounded-full"
                                  :src="member.avatar"
                                />
                                <img
                                  v-else
                                  class="w-10 h-10 rounded-full"
                                  src="nullUser.png"
                                />
                              </div>
                              <div class="font-medium text-gray-800">
                                {{ member.name }}
                              </div>
                            </div>
                          </td>
                          <td class="p-2 whitespace-nowrap">
                            <div class="text-left">{{ member.email }}</div>
                          </td>
                          <td class="p-2 whitespace-nowrap">
                            <div class="text-left font-medium text-green-500">
                              {{ format_date(member.created_at) }}
                            </div>
                          </td>
                          <td class="p-2 whitespace-nowrap">
                            <div class="text-md text-center">
                              <a
                                style="cursor: pointer"
                                @click.prevent="
                                  $inertia.get(
                                    route('view-member', { id: member.id })
                                  )
                                "
                                class="
                                bg-green-400
                                hover:bg-green-500
                                transition-colors
                                rounded-[8px]
                                px-[15px]
                                py-[4px]
                                text-white
                                focus:ring-2
                                ring-yellow-500"
                              >
                                  Edit
                              </a>

                              <a
                                style="cursor: pointer"
                                @click.prevent="
                                  $inertia.post(
                                    this.route('delete-member', {
                                      id: member.id,
                                    })
                                  )
                                "
                                class="
                                    ml-1
                                    bg-red-400
                                    hover:bg-red-500
                                    transition-colors
                                    rounded-[8px]
                                    px-[15px]
                                    py-[4px]
                                    text-white
                                    focus:ring-2
                                    ring-yellow-500
                                ">
                                  Delete
                              </a>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
  name: "Members",
  props: ["members"],
  components: {
    AppLayout,
  },
  data() {
    return {};
  },
  methods: {
    format_date: function (date) {
      return new Date(date).toLocaleDateString();
    },
  },
};
</script>
