import React from 'react';
import Webcam from 'react-webcam';


 const WebRTC = ({handler}) => {
    const webcamRef = React.useRef(null);
    const [capturedImage, setCapturedImage] = React.useState(null);

    
  
    // Function to handle capturing an image
    const captureImage = React.useCallback(() => {
      const imageSrc = webcamRef.current.getScreenshot();
      // Do something with the captured image
      setCapturedImage(imageSrc);
      handler(webcamRef.current.getScreenshot())
      
      console.log(imageSrc);
    }, [webcamRef]);
  
    return (
      <div className='text-center mr-20'>
          {capturedImage ? <img src={capturedImage} alt="Captured" />:
          <div>

        <Webcam
          audio={false}
          ref={webcamRef}
          className='rounded-lg h-80   '
          screenshotFormat="image/jpeg"
        />
        <button className='p-2 bg-blue-700 mt-4 px-10 rounded-md '  onClick={captureImage}>Capture</button>
        </div>
        }
      </div>
    );
  };
  
  export default WebRTC;