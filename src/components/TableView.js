import React from 'react'
import ProgressBar from '../ProgressBar'

export default function TableView({no,name,start,end,progress,isTitle ,borderClass}) {
  return (
    <div className={`  border-2   rounded-lg shadow executive-bg text-xl px-2 form-fonts pb-2  ${borderClass}`}>
     <div className='text-center space-y-3 py-5'>


    {/* <h1 >{no}.</h1> */}
    <h1>{name}</h1>
       
    <h1>Start Date {start}</h1>
    <h1>End Date {end}</h1>
    {
        isTitle?
        <h1>{progress}</h1>:
        <ProgressBar  progress={progress}  height={30} />
    }
    </div>
 </div>
  )
}
