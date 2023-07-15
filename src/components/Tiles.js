import React from 'react'

export default function Tiles({taskName,assignee,dueDate,status,color,priorityFile}) {
  return (
    <div className='flex  flex-wrap justify-center md:justify-between md:px-5 py-5 md:py-2  md:space-y-0 space-y-2  text-center rounded-md bg-tile  '>
            <h1>{taskName}</h1>
            <div className='flex px-14 flex-wrap md:justify-none justify-center md:space-y-0 space-y-3   md:space-x-10'>

            <h1>{assignee}</h1>
            <h1>{dueDate}</h1>

          
            <img className={`w-6  h-5 ${priorityFile=='Yellow.png'?'pl-2':''} `} src={process.env.PUBLIC_URL+`/assets/flags/${priorityFile}`}/>
      
            
             {status=='Completed' || status=='Incomplete'? 
             <h1 className={`${color} px-3  rounded-sm`}>{status}</h1>
             
             : 

             <select className={`bg-tile rounded-sm px-2` }>
                <option className='bg-blue-500'>In Progress</option>
                <option className='bg-blue-500'>Completed</option>
             </select>
}

            </div>
            </div>
  )
}
