import { reactive, computed, watch } from 'vue'

const state = reactive({ lines: {} })

try {
    const raw = localStorage.getItem('cart')
    if (raw) Object.assign(state.lines, JSON.parse(raw) ?? {})
} catch {}

watch(() => state.lines, v => localStorage.setItem('cart', JSON.stringify(v)), { deep: true })

const items = computed(() => Object.values(state.lines))
const count = computed(() => items.value.reduce((c,i)=>c+i.qty,0))
const total = computed(() => items.value.reduce((s,i)=>s+i.qty*i.price,0))

function add(p){ const c = state.lines[p.id]; c ? c.qty++ : state.lines[p.id] = { ...p, qty: 1 } }
function decrease(id){ const c = state.lines[id]; if(!c) return; c.qty>1?c.qty--:delete state.lines[id] }
function removeLine(id){ delete state.lines[id] }
function clear(){ for (const k of Object.keys(state.lines)) delete state.lines[k] }
function buildWhatsAppLink(){
    if (!items.value.length) return '#'
    const msg = items.value.map(l => `${l.name} x${l.qty} — $${(l.price*l.qty).toFixed(2)}`).join('\n')
    return `https://wa.me/?text=${encodeURIComponent(`Hello! I'd like to order:\n\n${msg}\n\nTotal: $${total.value.toFixed(2)}`)}`
}

export function useCart(){ return { items, count, total, add, decrease, removeLine, clear, buildWhatsAppLink } }
