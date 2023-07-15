import React, { useEffect, useState } from 'react'
import ProgressBar from './ProgressBar'
import MemberList from './MemberList'

export default function TableView({no,name,start,end,progress,task ,borderClass ,status,statusColor}) {

const [toggle,setToggle]=useState(false)

const changeToggle=()=>
{
  setToggle(!toggle)
}

useEffect(()=>
{
  
},[toggle])
  return (
    <div className={`  border-2   rounded-lg shadow executive-bg text-xl px-2 form-fonts pb-2  ${borderClass}`}>
     <div className='text-center space-y-3 py-5'>
  

     {
      status? '':<div className=' text-start'> 
      <button onClick={changeToggle}>
      <img  className=' w-5' src={process.env.PUBLIC_URL+'/assets/executive/addUser.png'}/>
      </button>
   {
    toggle?
   
      <div className='absolute -pb-10'>
{/* <h1 className='absolute left-44 z-10 text-md'>close</h1> */}
<button className='absolute z-10  left-48 ml-3' onClick={changeToggle}>

<img className='w-4   ' src={process.env.PUBLIC_URL+'/assets/form/tick.png'}/>
</button>
<div className='mt-2'>

      <MemberList/>
</div>
      </div>:''
}
      
       </div>
    }
    <h1>{name}</h1>
       
    <h1>Start Date {start}</h1>
    <h1>End Date {end}</h1>
    {
        task?

        <h1>{task}</h1>
        :
        <ProgressBar  progress={progress}  height={30} />
    }


    
    {
status?
<h1 className={`border-2  rounded-md  px-8 py-1 inline-block ${statusColor}`}>{status}</h1>:''

    }

   
    </div>
 </div>
  )
}
