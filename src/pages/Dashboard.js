import React from 'react'
import Navbar from '../components/Navbar'
import '../App.css'
import ProgressBar from '../components/ProgressBar'
import TableView from '../components/TableView'
import Profile from '../components/Profile'
import AddForm from '../components/AddForm'
import { useState,useEffect } from 'react'
// import dashboard from '../../public/assets/bg_login_signup/'

export default function Dashboard() {


  
  const [movement,setMovement]=useState(false)

  const onClick=()=>
  {
    if(movement===false)
    {
      setMovement(true)
      console.log(movement)
      document.getElementById("moveDiv").style.top='10%'
    }

    else
    {
      setMovement(false)
      document.getElementById("moveDiv").style.top='-100%'

    }

    
  }


  // const [toggle, setToggle] = useState(false)
  
  useEffect(() => {
 
  },[movement])

  // useEffect(()=>
  // {

  // },[movement])

  return (
    <div className='bg-[#fafafa] lg:flex  block justify-between lg:overflow-hidden'>
      
      
      <Navbar creator={true} click={onClick}/>
      
      <div className='lg:h-screen  w-screen lg:w-1/2 executive-background lg:overflow-hidden '>

      <h1 className='text-center pt-10  text-2xl  main-login-font'>Name Workspace</h1>

      </div>




     <div className=' lg:w-1/2 lg:h-[93vh] text-center pt-14 overflow-x-hidden lg:overflow-hidden lg:overflow-y-scroll    '>
      <div>
       <h1 className='main-login-font text-3xl '>Your Projects</h1>

       

<div className='lg:mt-20 mt-10 grid grid-cols-1 lg:grid-cols-2  mx-10  gap-6  '>



   
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
   
       

     </div>


</div>

      </div>

<div className='fixed pt-10 top-3/4 mt-14'>
<Profile/>
</div>


<AddForm Click={onClick} name={'Create'} input1Name={'Project Name'} input2Name={'Email'} input2Type={'email'}/>
</div>
 
  )
}
