import React from 'react'

export default function NavbarItem({imgName,name}) {
  return (
 
    <div className='w-8 space-x-4 flex text-center '>
        <img className='dashImg' src={process.env.PUBLIC_URL + `/assets/nav_icons/${imgName}`}  />
        <h1   className=' form-fonts dashtxt bg-white border-[1px] border-black  rounded-2xl py-1 px-4'>{name}</h1>
    
    </div>
  )
}
