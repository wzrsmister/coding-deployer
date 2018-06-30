import fetch from '@/utils/fetch'

export function getList(query) {
  return fetch({
    url: '/user/index',
    method: 'get',
    params: query
  })
}