import React from 'react'
import '../App.css'
import Navbar from '../components/Navbar'
import Tiles from '../components/Tiles'
import TaskHead from '../components/TaskHead'
import Drawer from '../components/Drawer'


export default function Project() {
    return (
        <div id='project-bg' className=' h-screen w-screen text-white overflow-x-hidden pb-10 scroll-smooth'>
            <Navbar isProject={true} />

            <div className='md:flex flex-wrap-reverse justify-evenly md:justify-between md:px-32 px-0 pt-10'>

                <h1 className='text-center main-login-font text-3xl font-semibold'>Project Name</h1>
                <div className=' md:space-x-8 md:px-0 px-4 justify-between h-10 flex  md:text-center '>
                    <h1 className='pt-4'>Project End Date</h1>
                    <Drawer />
                    {/* <img className='bg-white p-2 rounded-full cursor-pointer ' src={process.env.PUBLIC_URL + `/assets/project/notification.png`}/> */}
                </div>
            </div>

            <div>


                <TaskHead text={'Task Completed'} classes={'bg-green-700 '} />

                <div className='mx-20 md:mx-60 pt-10 form-fonts text-lg space-y-3' >
                    <Tiles taskName={'Complete Sign up'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Completed'} color={'bg-green-600'} />
                    <Tiles taskName={'Complete Sign up'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Completed'} color={'bg-green-600'} />
                    <Tiles taskName={'Complete Sign up'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Completed'} color={'bg-green-600'} />
                    <Tiles taskName={'Complete Sign up'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Completed'} color={'bg-green-600'} />
                </div>
            </div>

            <div>


<TaskHead text={'In Progress'} classes={'bg-[#1345B7] '} />

<div className='mx-20 md:mx-60 pt-10 form-fonts text-lg space-y-3' >
    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'In Progress'} color={'bg-red-600'} />
    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'In Progress'} color={'bg-red-600'} />
    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'In Progress'} color={'bg-red-600'} />
    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'In Progress'} color={'bg-red-600'} />

</div>
</div>


            <div>


                <TaskHead text={'Over Due'} classes={'bg-red-700 '} />

                <div className='mx-20 md:mx-60 pt-10 form-fonts text-lg space-y-3' >
                    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Incomplete'} color={'bg-red-600'} />
                    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Incomplete'} color={'bg-red-600'} />
                    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Incomplete'} color={'bg-red-600'} />
                    <Tiles taskName={'Complete Sign IN'} assignee={'Salman Tariq'} dueDate={'17/07/2023'} status={'Incomplete'} color={'bg-red-600'} />

                </div>
            </div>



        </div>
    )
}
