import React from 'react'
import Input from './Input'
import { useState,useEffect } from 'react'
export default function NavbarItem({imgName,name,classes}) {

  // const [movement,setMovement]=useState(false)

  // const onClick=()=>
  // {
  //   if(movement===false)
  //   {
  //     setMovement(true)
  //     console.log(movement)
  //     document.getElementById("moveDiv").style.top='10%'
  //   }

  //   else
  //   {
  //     setMovement(false)
  //     document.getElementById("moveDiv").style.top='-100%'

  //   }

    
  // }


  // // const [toggle, setToggle] = useState(false)
  
  // // useEffect(() => {
 
  // //   const check=()=>
  // //   {
      
  // //   const concernedElement = document.querySelector(".click-text");

  // //   document.addEventListener("mousedown", (event) => {
  // //     if (concernedElement.contains(event.target)) {
  // //       setToggle(true)
        
  // //     } else {
        
  // //       setToggle(false)
  // //     document.getElementById("moveDiv").style.top='-100%'
  // //     setMovement(false)

  // //     }
  // //   });
  // //   }

  // //   check()
  // // },[])

  // useEffect(()=>
  // {

  // },[movement])
  return (

    <div>
    
    
    {
        // name=='Create'?
        <button>

        <div className='hover:w-9 w-8 space-x-4 flex text-center '>

        <img className={`dashImg cursor-pointer  bg-white ${classes}`}  src={process.env.PUBLIC_URL + `/assets/nav_icons/${imgName}`}  />
    <h1   className=' form-fonts dashtxt bg-white border-[1px] border-black  rounded-2xl py-1 px-4'>{name}</h1>
    </div>
        </button>
    // :''
      }

</div>
//  <>
//  <button >
//     <div className='hover:w-9 w-8 space-x-4 flex text-center '>
//     {
//       name=='Create'?
//       // <button onClick={onClick}>
//       <img className={`dashImg cursor-pointer  bg-white ${classes}`}  src={process.env.PUBLIC_URL + `/assets/nav_icons/${imgName}`}  />
      
// // </button>
//  :

//         <img className={`dashImg cursor-pointer  bg-white ${classes}`}  src={process.env.PUBLIC_URL + `/assets/nav_icons/${imgName}`}  />
//       }
    
//         <h1   className=' form-fonts dashtxt bg-white border-[1px] border-black  rounded-2xl py-1 px-4'>{name}</h1>
//     </div>
//       </button>

//    </>
  )
}
