import React from 'react'
import '../App.css'
import Input from '../components/Input'
import { useState, useEffect } from 'react'
import WebRTC from '../components/WebRTC'

export default function WorkSpaceCreation() {

  const [formData, setFormData] = useState({
    workspace: '',
    img: '',
  })



  const [isCLicked,setClick]=useState(false)


  // const file = base64ToBlob(imageData, 'image/jpeg');

  const changeHandler = (e) => {

    setFormData({
      ...formData,
      [e.target.id]: e.target.value
    });

    console.log(formData)
  }



  const togggleClick=()=>{
    setClick(!isCLicked)
  }
  

  const cameraImagehandler=(img)=>
  {
    formData.img=img
    console.log('imagehsahis', formData.img)
  }

useEffect(()=>
{

},[isCLicked,formData])
  

  return (



    <div className='div-background w-scren h-screen  flex-wrap flex-wrap-reverse justify-center flex md:justify-between md:pt-0 pt-6 px-5 md:px-10  items-center text-white  '>

      <div className='space-y-8 md:mr-14 backdrop-blur-sm py-4 md:px-10 px-10 border-[1px] shadow-md rounded-xl pb-10' >

<form className='space-y-5'>



        <h1 className='text-[24px] md:text-[35px] form-fonts text-center font-light' >Details</h1>

        <div className='flex justify-between '>

          <div className='bg-white rounded-full w-20 p-4'>
            <label for='img'>

              <img className='cursor-pointer' src={process.env.PUBLIC_URL + '/assets/upload_image/upload.png'} alt='...' />
            </label>
            <input id='img' type='file' className='upload-bg hidden' value={formData.img} onChange={changeHandler} accept='image/*' />

            
</div>

<div className= ' mt-3 h-14 align-center w-[1px] bg-white'>
  
</div>

<button onClick={togggleClick}>

<div className='bg-white rounded-full w-20 p-4'>

<img className='cursor-pointer' src={process.env.PUBLIC_URL + '/assets/upload_image/camera.png'} alt='...' />

</div>
</button>
          </div>
       

        <Input type={"text"} id={"workspace"} value={formData.workspace} label={"Work Space Name"} onChange={changeHandler} />
        <Input type={"submit"} id={"create"} value={formData.password} label={""} onChange={changeHandler} btnValue={'Create'} isLast/>
</form>
        


      </div>

      {
        isCLicked?

        <WebRTC handler={cameraImagehandler}/>
        :
        <div className='text-center md:text-start px-5 md:mt-0 mt-14 '>
        <h1 className='text-[30px] md:text-[40px] main-login-font text-center'>Give your organization a <br></br> distinctive name that reflects  <br></br>its values, purpose, and <br></br> mission</h1>
      </div>
      }
      
      {
     
      <img src={formData.img}/>
      }

    </div>
  )
}
