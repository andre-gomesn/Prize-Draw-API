using app.Data;
using app.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace app.Controllers
{
    [Route("api/[controller]")]
    [ApiController]

    public class PrizeControlController : ControllerBase
    {
        private readonly DataContext _context;

        public PrizeControlController(DataContext context)
        {
            _context = context;
        }

        [HttpGet]
        public async Task<IActionResult> GetPrizeControl()
        {
            try
            {
                List<Prize_Control> list = await _context.TB_Prize_Control.ToListAsync();
                return Ok(list);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        [ApiExplorerSettings(IgnoreApi = true)]
        public async Task<IActionResult> ResetInventoryPrizeControl()
        {
            try
            {
                var inventory = await _context.TB_Prize_Control.FindAsync(2); // inventory control
                if (inventory == null)
                    return NotFound();

                inventory.ValueControl = "false";
                inventory.DateUpdate = DateTime.Now;

                await _context.SaveChangesAsync();

                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        [ApiExplorerSettings(IgnoreApi = true)]
        public async Task<IActionResult> ResetTimesPrizeControl()
        {
            try
            {
                var prizeControl = await _context.TB_Prize_Control.ToListAsync();
                if (prizeControl == null)
                    return NotFound();

                prizeControl.First(x => x.Id == 1).ValueControl = "";
                prizeControl.First(x => x.Id == 1).DateUpdate = DateTime.Now;
                prizeControl.First(x => x.Id == 3).ValueControl = "0";
                prizeControl.First(x => x.Id == 3).DateUpdate = DateTime.Now;

                await _context.SaveChangesAsync();

                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        [ApiExplorerSettings(IgnoreApi = true)]
        public async Task<IActionResult> UpdatePrizesAsync()
        {
            try
            {
                var prizesInDay = await _context.TB_Prize_Control.FindAsync(3); // prizes in day control
                if (prizesInDay == null)
                    return NotFound();

                prizesInDay.ValueControl = (int.Parse(prizesInDay.ValueControl) + 1).ToString();

                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        [ApiExplorerSettings(IgnoreApi = true)]
        public async Task<IActionResult> UpdatePrizesAsync()
        {
            try
            {
                var prizesInDay = await _context.TB_Prize_Control.FindAsync(3); // prizes in day control
                if (prizesInDay == null)
                    return NotFound();

                prizesInDay.ValueControl = (int.Parse(prizesInDay.ValueControl) + 1).ToString();

                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }



        [ApiExplorerSettings(IgnoreApi = true)]
        public async Task<IActionResult> SavePrizeByDay(int value)
        {
            try
            {
                var prizeControl = await _context.TB_Prize_Control.ToListAsync();
                if (prizeControl == null)
                    return NotFound();

                if (prizesInDay == null)
                    return NotFound();

                if(DateTime.Now.Date != prizeControl.First(x => x.Id == 1).DateUpdate.Date){
                    prizeControl.First(x => x.Id == 1).ValueControl = value.ToString();
                    prizeControl.First(x => x.Id == 1).DateUpdate = DateTime.Now;

                    prizeControl.First(x => x.Id == 3).ValueControl = "0";
                    prizeControl.First(x => x.Id == 3).DateUpdate = DateTime.Now;
                }

                int affectedRows = await _context.SaveChangesAsync(prizeControl);
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }
    }
}
