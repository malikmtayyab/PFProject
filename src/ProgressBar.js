import React from 'react'
import './App.css'


const ProgressBar = ({bgcolor,progress,height}) => {
	
	const Parentdiv = {
		height: height,
		width: '100px',
		backgroundColor: '#78c2ff',
		borderRadius: 40,
		border:'1px solid #1a598c'
		
	}
	
	const Childdiv = {
		
		width: `${progress}%`,
		height:height,
		backgroundColor: '#1a598c',
	borderRadius:40,
		textAlign: 'right',
		
	}
	
	const progresstext = {
		// padding: 10,
		// color: 'black',
		// fontWeight: 900
	}
		
	return (
		<div className='flex justify-center'>

	<div className='  ' id='progressBar' style={Parentdiv}>
	<div style={Childdiv}>
		<span className='text-white p-[10px]  form-fonts ' style={progresstext}>{`${progress}%`}</span>
	</div>
	</div>
		</div>
	)
}

export default ProgressBar;
