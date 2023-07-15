import React from 'react'
import Navbar from '../components/Navbar'
import '../App.css'
import TableView from '../components/TableView'

export default function Projects() {
  return (
    <div className='bg-[#fafafa]  pb-20 '> 
    
    <Navbar/>
<div className='flex justify-center '>
<div className='text-center'>

    <img  className='w-72' src={process.env.PUBLIC_URL+`/assets/projectPage/project.jpg`}/>
    <h1 className='main-login-font text-2xl'>Your Projects</h1>
</div>
    
</div>

<div className='lg:mt-20 mt-10 grid grid-cols-1 lg:grid-cols-2   mx-10 md:mx-20  gap-8  '>


<TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'}  task={'Complete Login'} status={'Incomplete'} statusColor={'bg-red-500 form-fonts text-white border-red-800'} />
<TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'}  task={'Complete Login'} status={'Complete'} statusColor={'bg-green-500 form-fonts text-white border-green-800'} />
<TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'}  task={'Complete Login'} status={'Complete'} statusColor={'bg-green-500 form-fonts text-white border-green-800'} />


</div>


    </div>
  )
}
