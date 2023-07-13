import React from 'react'

export default function TaskHead({classes,text}) {
  return (
    <div className='text-xl flex-1  justify-between md:mx-52 mx-10 md:mr-60 mt-10'>
    <h1 className={`form-fonts text-xl pl-4 p-2 border-b-2 rounded-md ${classes}`} > {text}</h1>
</div>
  )
}
