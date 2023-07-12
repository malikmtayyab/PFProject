import React from 'react'
import NavbarItem from './NavbarItem'

export default function Navbar() {
  return (
    <div className='top-1/3 fixed space-y-5 ml-4'>

        <NavbarItem imgName={'dashboard.png'} name={'Dashboard'} />
        <NavbarItem imgName={'projects.png'} name={'Projects'}/>
        <NavbarItem imgName={'create.png'} name={'Create'}/>
    </div>
  )
}
