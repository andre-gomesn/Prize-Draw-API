using app.Data;
using app.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace app.Controllers
{
    [Route("api/[controller]")]
    [ApiController]

    public class PrizeLogController : ControllerBase
    {
        private readonly DataContext _context;

        public PrizeLogController(DataContext context)
        {
            _context = context;
        }

        [HttpPost]
        public async Task<IActionResult> AddSelectedPrizeAsync(int prize, int? idUser = null)
        {
            try
            {
                Logs_Prize logPrize = new Logs_Prize
                {
                    Prize = prize,
                    IdUser = idUser
                };
                await _context.TB_Logs_Prize.AddAsync(logPrize);
                await _context.SaveChangesAsync();
                return Ok();
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }

        // public async int IntentoryLogic(){
            
        // }
    }
}
