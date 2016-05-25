import React from 'react'

export default function Foo({ authData }) {
  return (
    <div>{`I am Tracker! Welcome ${authData.name}`}</div>
  )
}
