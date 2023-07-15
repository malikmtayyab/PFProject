import React from 'react'
import { useState, useEffect,useRef } from 'react'


export default function Profile() {

  const [toggle, setToggle] = useState(false)
  
  useEffect(() => {
 
    const check=()=>
    {
      
    const concernedElement = document.querySelector(".click-text");

    document.addEventListener("mousedown", (event) => {
      if (concernedElement.contains(event.target)) {
        setToggle(true)
        console.log('in scope')
      } else {
        
        setToggle(false)
      }
    });
    }

    check()
  },[])


  return (
    <div className=' ml-4 flex space-x-4 click-text z-10'>
      <div className='w-10'>
        <button >

          <img src={process.env.PUBLIC_URL + `/assets/profile/profile_pic.png`} />
        </button>
      </div>

      {
        toggle === true ?


          <div className='bg-[#fafafa]  border-2  space-y-2 text-center p-2 rounded-lg form-fonts'>
            <button>

            <h1>Change Passowrd</h1>
            </button>

            <hr></hr>
            
            <button>

            <h1>Logout</h1>
            </button>
          </div> : ''
      }


    </div>
  )
}
