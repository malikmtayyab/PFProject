import React from 'react'
import NavbarItem from './NavbarItem'

export default function Navbar({isProject,creator}) {
  return (
    <div className={`${creator?'top-1/3':'top-1/2'} fixed space-y-5 ml-4`}>

   {
    creator?
     <NavbarItem imgName={'dashboard.png'} name={'Dashboard'} classes={isProject?'px-2 py-2 rounded-md':''} />:''
  }

  
        <NavbarItem imgName={'projects.png'} name={'Projects'} classes={isProject?'px-2 py-2 rounded-md':''}/>

        {
          creator?
          <NavbarItem imgName={'create.png'} name={'Create'} classes={isProject?'px-2 py-2 rounded-md':''}/>:''
        }
    </div>
  )
}
