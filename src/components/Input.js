
import '../App.css'

const Input = ({ label, id, type, value, onChange, isLast, disable,btnValue }) => {
    return (
        <div className="text-center md:text-start">

            <label className="font-light md:pl-2 text-[14px] md:text-[18px] form-fonts font-light mb-8 text-lg">{label}</label>
            <br></br>
            {
                isLast ?
                    <input type='submit' className='bg-blue-800 pointer-pointer border-blue-900 border-[1px] w-60 md:w-80 h-12  bg-transparent  border-white  rounded-3xl' value={btnValue} />
                    :

                    <input className={"w-60 md:w-80 h-12  pl-3 bg-transparent border-[1px] border-white  rounded-3xl"} required id={id} type={type} value={value != '0' ? value : ''} onChange={onChange} />
            }

        </div>
    );
};

export default Input;