<template>
  <div style="margin-right: -0.5rem">
    <!-- Confirm Action Modal -->
    <component
      v-if="actionModalVisible"
      :show="actionModalVisible"
      class="text-left"
      :is="selectedAction?.component"
      :working="working"
      :selected-resources="selectedResources"
      :resource-name="resourceName"
      :action="selectedAction"
      :errors="errors"
      @confirm="runAction"
      @close="closeConfirmationModal"
    />

    <component
      v-if="responseModalVisible"
      :show="responseModalVisible"
      :is="actionResponseData?.modal"
      @confirm="handleResponseModalConfirm"
      @close="handleResponseModalClose"
      :data="actionResponseData"
    />

    <div class="ml-auto flex items-center pl-4">
      <div v-if="actions.length > 0" class="flex">
        <div
          v-if="shouldShowButtons"
          :dusk="`${triggerDuskAttribute}-button-group`"
          class="flex gap-4 py-0"
          :class="{'mr-4': shouldShowDropdown}"
        >
          <template v-for="action in actions">
            <button
              v-if="action.showAsButton"
              :key="action.uriKey"
              :dusk="`${triggerDuskAttribute}-${action.uriKey}`"
              @click="() => handleActionClick(action.uriKey)"
              :title="action.name"
              :destructive="action.destructive"
              class="relative inline-flex items-center justify-center px-3 text-sm font-bold text-white rounded shadow cursor-pointer bg-primary-500 hover:bg-primary-400 dark:text-gray-900 focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 h-9">
              {{ action.name }}
            </button>
          </template>
        </div>

        <Dropdown v-if="shouldShowDropdown">
          <slot name="sr-only">
            <span class="sr-only">{{ __('Standalone Actions') }}</span>
          </slot>
          <slot name="trigger">
            <DropdownTrigger
              :dusk="triggerDuskAttribute"
              :show-arrow="false"
              class="rounded hover:bg-gray-200 dark:hover:bg-gray-800 focus:outline-none focus:ring"
            >
              <BasicButton component="span">
                <Icon :solid="true" type="dots-horizontal"/>
              </BasicButton>
            </DropdownTrigger>
          </slot>

          <template #menu>
            <DropdownMenu width="auto" class="px-1">
              <ScrollWrap
                :height="250"
                class="divide-y divide-gray-100 dark:divide-gray-800 divide-solid"
              >
                <slot/>

                <div v-if="actions.length > 0" class="py-1">
                  <template v-for="action in actions">
                    <DropdownMenuItem

                      v-if="!action.showAsButton"
                      :key="action.uriKey"
                      :data-action-id="action.uriKey"
                      as="button"
                      class="border-none"
                      @click="() => handleActionClick(action.uriKey)"
                      :title="action.name"
                      :destructive="action.destructive"
                    >
                      {{ action.name }}
                    </DropdownMenuItem>
                  </template>
                </div>
              </ScrollWrap>
            </DropdownMenu>
          </template>
        </Dropdown>
      </div>
    </div>
  </div>
</template>

<script setup>
import {useActions} from '@/composables/useActions'
import {computed} from 'vue'
import {useStore} from 'vuex'

const store = useStore()

const emitter = defineEmits(['actionExecuted'])

const props = defineProps({
  resourceName: {},
  viaResource: {},
  viaResourceId: {},
  viaRelationship: {},
  relationshipType: {},
  actions: {type: Array, default: []},
  selectedResources: {type: [Array, String], default: () => []},
  endpoint: {type: String, default: null},
  triggerDuskAttribute: {type: String, default: null},
})

const {
  errors,
  actionModalVisible,
  responseModalVisible,
  openConfirmationModal,
  closeConfirmationModal,
  closeResponseModal,
  handleActionClick,
  selectedAction,
  working,
  executeAction,
  actionResponseData,
} = useActions(props, emitter, store)

const runAction = () => executeAction(() => emitter('actionExecuted'))

const handleResponseModalConfirm = () => {
  closeResponseModal()
  emitter('actionExecuted')
}

const handleResponseModalClose = () => {
  closeResponseModal()
  emitter('actionExecuted')
}

const shouldShowButtons = computed(() => {
  const showInDropdownList = props.actions.filter(action => action.showAsButton === true);

  return showInDropdownList.length > 0;
});

const shouldShowDropdown = computed(() => {
  const showInDropdownList = props.actions.filter(action => action.showAsButton === false);

  return showInDropdownList.length > 0;
});


</script>
