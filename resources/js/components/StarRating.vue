<template>
  <div class="p-4 bg-white rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-3">Beri Rating</h3>

    <!-- Bintang -->
    <div class="flex gap-1 mb-4">
      <button
        v-for="star in 5"
        :key="star"
        @click="selected = star"
        class="text-3xl transition-transform hover:scale-110"
      >
        <span :class="star <= selected ? 'text-yellow-400' : 'text-gray-300'">★</span>
      </button>
    </div>

    <!-- Komentar -->
    <textarea
      v-model="comment"
      placeholder="Tulis komentar (opsional)..."
      class="w-full border rounded p-2 text-sm mb-3"
      rows="3"
    ></textarea>

    <!-- Tombol Submit -->
    <button
      @click="submit"
      :disabled="!selected"
      class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50 hover:bg-blue-700"
    >
      Kirim Rating
    </button>

    <p v-if="message" class="mt-2 text-sm text-green-600">{{ message }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  orderId: Number,
  ratedId: Number,
  type: String // 'driver' atau 'passenger'
})

const selected = ref(0)
const comment = ref('')
const message = ref('')

async function submit() {
  try {
    await axios.post('/api/ratings', {
      order_id: props.orderId,
      rated_id: props.ratedId,
      stars: selected.value,
      type: props.type,
      comment: comment.value,
    })
    message.value = 'Rating berhasil dikirim!'
  } catch (e) {
    message.value = e.response?.data?.message || 'Gagal mengirim rating'
  }
}
</script>