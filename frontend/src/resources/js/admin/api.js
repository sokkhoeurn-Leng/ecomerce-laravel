const BASE = 'http://localhost:8000/api'

function getHeaders(extra = {}) {
  const token = localStorage.getItem('token')
  return {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
    ...extra,
  }
}

export async function apiGet(path) {
  const res = await fetch(`${BASE}${path}`, { headers: getHeaders() })
  return res.json()
}

export async function apiPost(path, body) {
  const isFormData = body instanceof FormData
  const res = await fetch(`${BASE}${path}`, {
    method: 'POST',
    headers: getHeaders(isFormData ? {} : { 'Content-Type': 'application/json' }),
    body: isFormData ? body : JSON.stringify(body),
  })
  return res
}

export async function apiPut(path, body) {
  const isFormData = body instanceof FormData
  const res = await fetch(`${BASE}${path}`, {
    method: 'PUT',
    headers: getHeaders(isFormData ? {} : { 'Content-Type': 'application/json' }),
    body: isFormData ? body : JSON.stringify(body),
  })
  return res
}

export async function apiDelete(path) {
  const res = await fetch(`${BASE}${path}`, {
    method: 'DELETE',
    headers: getHeaders(),
  })
  return res
}
