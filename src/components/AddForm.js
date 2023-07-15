import React from 'react'
import Input from './Input'
import MemberList from './MemberList'
import { useState, useEffect } from 'react'
import '../App.css'
export default function AddForm({ input1Name, input2Name, input2Type, name, Click }) {

  const [toggleMembers, setToggleMembers] = useState(false)
  const [formData, setFormData] = useState({
    name: '',
    priority: '',
    email: '',
    endDate: '',
    membersMail: ''

  })

  const clickAddMembers = () => {
    setToggleMembers(!toggleMembers)
  }


  const changeHandler = (e) => {

    setFormData({
      ...formData,
      [e.target.id]: e.target.value
    });

  }

  useEffect(() => {
    console.log(formData)

  }, [formData])


  return (
    <form>

      <div className='text-black '>

        {

          <div id='moveDiv' className=' click-text transition-all z-30 fixed delay-200 duration-500 left-[12%] md:left-1/3 -top-full w-72  md:w-1/3 bg-white h-3/4  md:h-2/3 rounded-xl 3 border-black border-2 ' >
            <div className='  space-y-3 '>
              <div className='flex  mx-4 justify-between'>
                <h1></h1>
                <img className='w-16 pt-5 ' src={process.env.PUBLIC_URL + `/assets/form/createProject.png`} />

                <button onClick={Click}>

                  <img className='w-8 h-8 ' src={process.env.PUBLIC_URL + `/assets/form/close.png`} />
                </button>
              </div>

              <div className='grid grid-cols-1 place-items-center space-y-2 '>

                <Input type={"text"} id={"name"} value={formData.name} label={input1Name} inputClasses={'border-[2px] border-[#2980ba] h-9 rounded-lg'} onChange={changeHandler} />
                {
                  input2Type == 'radio' ?
                    <div className='block md:flex  md:space-x-20 pt-3 text-lg form-fonts md:text-start text-center '>
                      <label>Task Priority</label>
                      <div className='space-x-5 flex' >
                        <div className='space-x-1'>

                          <input id='priority' name='priority' value='High' type='radio' onClick={changeHandler}></input>
                          <label>High</label>
                        </div>

                        <div className='space-x-1'>

                          <input id='priority' name='priority' value='Normal' type='radio' onClick={changeHandler}></input>
                          <label>Normal</label>
                        </div>
                      </div>
                    </div>
                    :
                    <Input type={'email'} id={"email"} value={formData.email} label={input2Name} inputClasses={'border-[2px] border-[#2980ba] h-9 rounded-lg'} onChange={changeHandler} />
                }
                <Input type={"date"} id={"endDate"} value={formData.endDate} label={"End Date"} inputClasses={'border-[2px] border-[#2980ba] w-2 h-9 rounded-lg'} onChange={changeHandler} />

                <div>

                  {
                    input2Type === 'radio' ?
                      <div className='mt-3'>

                        <label className="font-light md:pl-2 text-[14px] md:text-[18px] form-fonts font-light mb-8 text-lg ">Select Member</label>

                        <select id='membersMail' className=' form-fonts w-full h-10 border-2 rounded-lg  border-[#2980ba]' value={formData.membersMail} onChange={changeHandler}>
                          <option className=''>email@gmail.com</option>
                          <option className=''>email@gmail.com</option>
                        </select>
                      </div>

                      :

                      <div className='text-center grid items-center  mt-4'>
                        <button className='flex justify-center items-center space-x-2 ' onClick={clickAddMembers} >
                          <img className='w-10' src={process.env.PUBLIC_URL + `/assets/form/addMembers.png`} />


                          <h1 className='form-fonts text-lg'>Add Members</h1>
                        </button>
                        {

                          toggleMembers ?
                            <div className='text-end fixed bg-white border-2 left-1/2 top-1/2 mt-10  rounded-md p-2' >

                              <button onClick={clickAddMembers} className='pr-8'>

                                <img className='w-4' src={process.env.PUBLIC_URL + `/assets/form/tick.png`} />
                              </button>


                              <MemberList />
                            </div>
                            : ''
                        }

                      </div>

                  }
                  <Input type={"submit"} id={"create"} btnValue={name} label={""} inputClasses={'border-[2px] bg-[#1b598c]  h-9 rounded-lg text-white'} isLast />

                </div>


              </div>

            </div>
          </div>

        }
      </div>

    </form>
  )
}
